<?php

namespace App\Http\Controllers;

use App\Models\LoyaltyProgram;
use App\Models\LoyaltyPoint;
use App\Models\LoyaltyReward;
use App\Models\Salon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LoyaltyProgramController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $salon = Auth::user()->salon;
        
        if (!$salon) {
            return redirect()->route('salon.profile')->with('error', 'Please complete your salon profile first.');
        }

        $loyaltyPrograms = $salon->loyaltyPrograms()->paginate(15);

        return view('salon.loyalty.index', compact('loyaltyPrograms', 'salon'));
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

        return view('salon.loyalty.create', compact('salon'));
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
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'points_per_dollar' => 'required|integer|min:1',
            'points_required' => 'required|integer|min:1',
            'reward_value' => 'required|numeric|min:0.01',
            'is_active' => 'boolean',
        ]);

        $loyaltyProgram = $salon->loyaltyPrograms()->create([
            'name' => $request->name,
            'description' => $request->description,
            'points_per_dollar' => $request->points_per_dollar,
            'points_required' => $request->points_required,
            'reward_value' => $request->reward_value,
            'is_active' => $request->is_active ?? true,
        ]);

        return redirect()->route('salon.loyalty.index')
            ->with('success', 'Loyalty program created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(LoyaltyProgram $loyaltyProgram)
    {
        // Ensure the loyalty program belongs to the current salon
        if ($loyaltyProgram->salon_id !== Auth::user()->salon_id) {
            abort(403);
        }

        // Get customer points and rewards
        $customerPoints = $loyaltyProgram->loyaltyPoints()
            ->with('user')
            ->orderByDesc('created_at')
            ->paginate(20);

        $customerRewards = $loyaltyProgram->loyaltyRewards()
            ->with('user')
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('salon.loyalty.show', compact('loyaltyProgram', 'customerPoints', 'customerRewards'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LoyaltyProgram $loyaltyProgram)
    {
        // Ensure the loyalty program belongs to the current salon
        if ($loyaltyProgram->salon_id !== Auth::user()->salon_id) {
            abort(403);
        }

        $salon = Auth::user()->salon;

        return view('salon.loyalty.edit', compact('loyaltyProgram', 'salon'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LoyaltyProgram $loyaltyProgram)
    {
        // Ensure the loyalty program belongs to the current salon
        if ($loyaltyProgram->salon_id !== Auth::user()->salon_id) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'points_per_dollar' => 'required|integer|min:1',
            'points_required' => 'required|integer|min:1',
            'reward_value' => 'required|numeric|min:0.01',
            'is_active' => 'boolean',
        ]);

        $loyaltyProgram->update([
            'name' => $request->name,
            'description' => $request->description,
            'points_per_dollar' => $request->points_per_dollar,
            'points_required' => $request->points_required,
            'reward_value' => $request->reward_value,
            'is_active' => $request->is_active ?? true,
        ]);

        return redirect()->route('salon.loyalty.index')
            ->with('success', 'Loyalty program updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LoyaltyProgram $loyaltyProgram)
    {
        // Ensure the loyalty program belongs to the current salon
        if ($loyaltyProgram->salon_id !== Auth::user()->salon_id) {
            abort(403);
        }

        $loyaltyProgram->delete();

        return redirect()->route('salon.loyalty.index')
            ->with('success', 'Loyalty program deleted successfully.');
    }

    /**
     * Award points to a customer
     */
    public function awardPoints(Request $request, LoyaltyProgram $loyaltyProgram)
    {
        // Ensure the loyalty program belongs to the current salon
        if ($loyaltyProgram->salon_id !== Auth::user()->salon_id) {
            abort(403);
        }

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'points' => 'required|integer|min:1',
            'source' => 'required|string',
            'description' => 'nullable|string',
        ]);

        // Check if user is a customer of this salon
        $user = User::find($request->user_id);
        if ($user->salon_id !== $loyaltyProgram->salon_id || !$user->hasRole('customer')) {
            return response()->json(['success' => false, 'message' => 'Invalid customer selected.']);
        }

        // Create loyalty points record
        $loyaltyPoint = $loyaltyProgram->loyaltyPoints()->create([
            'user_id' => $request->user_id,
            'salon_id' => $loyaltyProgram->salon_id,
            'points' => $request->points,
            'source' => $request->source,
            'description' => $request->description,
        ]);

        return response()->json(['success' => true, 'message' => 'Points awarded successfully!', 'points' => $loyaltyPoint]);
    }

    /**
     * Redeem points for a reward
     */
    public function redeemPoints(Request $request, LoyaltyProgram $loyaltyProgram)
    {
        // Ensure the loyalty program belongs to the current salon
        if ($loyaltyProgram->salon_id !== Auth::user()->salon_id) {
            abort(403);
        }

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'points_to_redeem' => 'required|integer|min:1',
            'reward_type' => 'required|in:discount,free_service,product',
            'description' => 'nullable|string',
        ]);

        // Check if user is a customer of this salon
        $user = User::find($request->user_id);
        if ($user->salon_id !== $loyaltyProgram->salon_id || !$user->hasRole('customer')) {
            return response()->json(['success' => false, 'message' => 'Invalid customer selected.']);
        }

        // Check if user has enough points
        $totalPoints = $user->loyaltyPoints()
            ->where('loyalty_program_id', $loyaltyProgram->id)
            ->where('is_redeemed', false)
            ->sum('points');

        if ($totalPoints < $request->points_to_redeem) {
            return response()->json(['success' => false, 'message' => 'Insufficient points.']);
        }

        // Calculate reward value
        $rewardValue = ($request->points_to_redeem / $loyaltyProgram->points_required) * $loyaltyProgram->reward_value;

        // Create reward record
        $reward = $loyaltyProgram->loyaltyRewards()->create([
            'user_id' => $request->user_id,
            'salon_id' => $loyaltyProgram->salon_id,
            'points_redeemed' => $request->points_to_redeem,
            'value' => $rewardValue,
            'type' => $request->reward_type,
            'description' => $request->description,
            'code' => strtoupper(uniqid('REWARD_')),
        ]);

        // Mark points as redeemed
        $pointsToRedeem = $request->points_to_redeem;
        $loyaltyPoints = $user->loyaltyPoints()
            ->where('loyalty_program_id', $loyaltyProgram->id)
            ->where('is_redeemed', false)
            ->orderBy('created_at')
            ->get();

        foreach ($loyaltyPoints as $point) {
            if ($pointsToRedeem <= 0) break;
            
            if ($point->points <= $pointsToRedeem) {
                $point->update(['is_redeemed' => true]);
                $pointsToRedeem -= $point->points;
            } else {
                // Split the point record
                $point->decrement('points', $pointsToRedeem);
                $point->newQuery()->create([
                    'user_id' => $point->user_id,
                    'salon_id' => $point->salon_id,
                    'loyalty_program_id' => $point->loyalty_program_id,
                    'points' => $pointsToRedeem,
                    'source' => $point->source,
                    'sourceable_type' => $point->sourceable_type,
                    'sourceable_id' => $point->sourceable_id,
                    'description' => $point->description . ' (redeemed)',
                    'is_redeemed' => true,
                ]);
                $pointsToRedeem = 0;
            }
        }

        return response()->json(['success' => true, 'message' => 'Points redeemed successfully!', 'reward' => $reward]);
    }

    /**
     * Get customer points balance
     */
    public function customerPoints($userId, LoyaltyProgram $loyaltyProgram)
    {
        // Ensure the loyalty program belongs to the current salon
        if ($loyaltyProgram->salon_id !== Auth::user()->salon_id) {
            abort(403);
        }

        $user = User::find($userId);
        if ($user->salon_id !== $loyaltyProgram->salon_id || !$user->hasRole('customer')) {
            return response()->json(['success' => false, 'message' => 'Invalid customer.']);
        }

        $totalPoints = $user->loyaltyPoints()
            ->where('loyalty_program_id', $loyaltyProgram->id)
            ->where('is_redeemed', false)
            ->sum('points');

        return response()->json(['success' => true, 'points' => $totalPoints]);
    }
}
