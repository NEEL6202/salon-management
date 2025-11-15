<?php

namespace App\Http\Controllers;

use App\Models\GiftCard;
use App\Models\Salon;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class GiftCardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $salon = Auth::user()->salon;
        
        if (!$salon) {
            return redirect()->route('salon.profile')->with('error', 'Please complete your salon profile first.');
        }

        $search = $request->get('search');
        $status = $request->get('status'); // all, active, expired, redeemed

        $giftCardsQuery = $salon->giftCards()->with(['customer', 'createdBy']);

        if ($search) {
            $giftCardsQuery->where(function($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                    ->orWhereHas('customer', function($cq) use ($search) {
                        $cq->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        switch ($status) {
            case 'active':
                $giftCardsQuery->where('is_active', true)
                    ->where(function($q) {
                        $q->whereNull('expires_at')
                            ->orWhere('expires_at', '>', now());
                    })
                    ->whereNull('redeemed_at');
                break;
            case 'expired':
                $giftCardsQuery->where('expires_at', '<', now());
                break;
            case 'redeemed':
                $giftCardsQuery->whereNotNull('redeemed_at');
                break;
        }

        $giftCards = $giftCardsQuery->orderByDesc('created_at')->paginate(15);

        return view('salon.gift-cards.index', compact('giftCards', 'salon', 'search', 'status'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $salon = Auth::user()->salon;
        
        if (!$salon) {
            return redirect()->route('salon.profile')->with('error', 'Please complete your salon profile first.');
        }

        $customers = $salon->users()
            ->whereHas('roles', function($q) {
                $q->where('name', 'customer');
            })
            ->orderBy('name')
            ->get();

        return view('salon.gift-cards.create', compact('salon', 'customers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $salon = Auth::user()->salon;
        
        if (!$salon) {
            return redirect()->route('salon.profile')->with('error', 'Please complete your salon profile first.');
        }

        $request->validate([
            'customer_id' => 'nullable|exists:users,id',
            'amount' => 'required|numeric|min:1|max:1000',
            'message' => 'nullable|string|max:500',
            'expires_in_days' => 'nullable|integer|min:1|max:365',
        ]);

        // Check if customer belongs to this salon
        if ($request->customer_id) {
            $customer = User::find($request->customer_id);
            if ($customer->salon_id !== $salon->id || !$customer->hasRole('customer')) {
                return back()->withErrors(['customer_id' => 'Invalid customer selected.']);
            }
        }

        $expiresAt = $request->expires_in_days ? now()->addDays($request->expires_in_days) : null;

        $giftCard = $salon->giftCards()->create([
            'code' => strtoupper(Str::random(12)),
            'created_by' => Auth::id(),
            'customer_id' => $request->customer_id,
            'initial_amount' => $request->amount,
            'balance' => $request->amount,
            'message' => $request->message,
            'expires_at' => $expiresAt,
            'is_active' => true,
        ]);

        return redirect()->route('salon.gift-cards.index')
            ->with('success', 'Gift card created successfully. Code: ' . $giftCard->code);
    }

    /**
     * Display the specified resource.
     */
    public function show(GiftCard $giftCard)
    {
        // Ensure the gift card belongs to the current salon
        if ($giftCard->salon_id !== Auth::user()->salon_id) {
            abort(403);
        }

        $giftCard->load(['customer', 'createdBy', 'salon']);

        return view('salon.gift-cards.show', compact('giftCard'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(GiftCard $giftCard)
    {
        // Ensure the gift card belongs to the current salon
        if ($giftCard->salon_id !== Auth::user()->salon_id) {
            abort(403);
        }

        $salon = Auth::user()->salon;
        $customers = $salon->users()
            ->whereHas('roles', function($q) {
                $q->where('name', 'customer');
            })
            ->orderBy('name')
            ->get();

        return view('salon.gift-cards.edit', compact('giftCard', 'salon', 'customers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, GiftCard $giftCard)
    {
        // Ensure the gift card belongs to the current salon
        if ($giftCard->salon_id !== Auth::user()->salon_id) {
            abort(403);
        }

        $request->validate([
            'customer_id' => 'nullable|exists:users,id',
            'message' => 'nullable|string|max:500',
            'is_active' => 'boolean',
        ]);

        // Check if customer belongs to this salon
        if ($request->customer_id) {
            $customer = User::find($request->customer_id);
            if ($customer->salon_id !== $giftCard->salon_id || !$customer->hasRole('customer')) {
                return back()->withErrors(['customer_id' => 'Invalid customer selected.']);
            }
        }

        $giftCard->update([
            'customer_id' => $request->customer_id,
            'message' => $request->message,
            'is_active' => $request->is_active ?? $giftCard->is_active,
        ]);

        return redirect()->route('salon.gift-cards.index')
            ->with('success', 'Gift card updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GiftCard $giftCard)
    {
        // Ensure the gift card belongs to the current salon
        if ($giftCard->salon_id !== Auth::user()->salon_id) {
            abort(403);
        }

        // Can only delete if not redeemed
        if ($giftCard->isRedeemed()) {
            return back()->with('error', 'Cannot delete a redeemed gift card.');
        }

        $giftCard->delete();

        return redirect()->route('salon.gift-cards.index')
            ->with('success', 'Gift card deleted successfully.');
    }

    /**
     * Redeem a gift card
     */
    public function redeem(Request $request, GiftCard $giftCard)
    {
        // Ensure the gift card belongs to the current salon
        if ($giftCard->salon_id !== Auth::user()->salon_id) {
            abort(403);
        }

        $request->validate([
            'code' => 'required|string|exists:gift_cards,code',
        ]);

        // Verify the gift card
        if (!$giftCard->isActive()) {
            return response()->json(['success' => false, 'message' => 'This gift card is not active or has expired.']);
        }

        if ($giftCard->balance <= 0) {
            return response()->json(['success' => false, 'message' => 'This gift card has no remaining balance.']);
        }

        return response()->json([
            'success' => true, 
            'message' => 'Gift card is valid.', 
            'code' => $giftCard->code,
            'balance' => $giftCard->balance,
            'expires_at' => $giftCard->expires_at ? $giftCard->expires_at->format('M d, Y') : null,
        ]);
    }

    /**
     * Apply gift card to an order/appointment
     */
    public function apply(Request $request)
    {
        $request->validate([
            'code' => 'required|string|exists:gift_cards,code',
            'amount' => 'required|numeric|min:0.01',
        ]);

        $giftCard = GiftCard::where('code', $request->code)->first();

        // Ensure the gift card belongs to the current salon
        if ($giftCard->salon_id !== Auth::user()->salon_id) {
            return response()->json(['success' => false, 'message' => 'Invalid gift card.']);
        }

        // Verify the gift card
        if (!$giftCard->isActive()) {
            return response()->json(['success' => false, 'message' => 'This gift card is not active or has expired.']);
        }

        if ($giftCard->balance <= 0) {
            return response()->json(['success' => false, 'message' => 'This gift card has no remaining balance.']);
        }

        // Calculate amount to deduct
        $deductAmount = min($request->amount, $giftCard->balance);

        // Update gift card balance
        $giftCard->decrement('balance', $deductAmount);
        
        // Mark as redeemed if this is the first use
        if (is_null($giftCard->redeemed_at)) {
            $giftCard->update(['redeemed_at' => now()]);
        }

        return response()->json([
            'success' => true, 
            'message' => 'Gift card applied successfully.', 
            'deducted_amount' => $deductAmount,
            'remaining_balance' => $giftCard->balance,
        ]);
    }
}
