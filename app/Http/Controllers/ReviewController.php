<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Salon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ReviewController extends Controller
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
        $status = $request->get('status', 'all'); // all, approved, pending, featured
        $rating = $request->get('rating');

        $reviewsQuery = $salon->reviews()->with(['customer', 'service', 'employee']);

        if ($search) {
            $reviewsQuery->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('review', 'like', "%{$search}%")
                    ->orWhereHas('customer', function($cq) use ($search) {
                        $cq->where('name', 'like', "%{$search}%");
                    });
            });
        }

        switch ($status) {
            case 'approved':
                $reviewsQuery->where('is_approved', true);
                break;
            case 'pending':
                $reviewsQuery->where('is_approved', false);
                break;
            case 'featured':
                $reviewsQuery->where('is_featured', true);
                break;
        }

        if ($rating) {
            $reviewsQuery->where('rating', $rating);
        }

        $reviews = $reviewsQuery->orderByDesc('created_at')->paginate(15);

        return view('salon.reviews.index', compact('reviews', 'salon', 'search', 'status', 'rating'));
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

        $services = $salon->services()->orderBy('name')->get();
        $employees = $salon->users()
            ->whereHas('roles', function($q) {
                $q->whereIn('name', ['manager', 'employee']);
            })
            ->orderBy('name')
            ->get();

        return view('salon.reviews.create', compact('salon', 'customers', 'services', 'employees'));
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
            'customer_id' => 'required|exists:users,id',
            'service_id' => 'nullable|exists:services,id',
            'employee_id' => 'nullable|exists:users,id',
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'nullable|string|max:255',
            'review' => 'required|string|max:1000',
            'photos' => 'nullable|array|max:5',
            'photos.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_featured' => 'boolean',
        ]);

        // Check if customer belongs to this salon
        $customer = \App\Models\User::find($request->customer_id);
        if ($customer->salon_id !== $salon->id || !$customer->hasRole('customer')) {
            return back()->withErrors(['customer_id' => 'Invalid customer selected.']);
        }

        // Check if service belongs to this salon
        if ($request->service_id) {
            $service = \App\Models\Service::find($request->service_id);
            if ($service->salon_id !== $salon->id) {
                return back()->withErrors(['service_id' => 'Invalid service selected.']);
            }
        }

        // Check if employee belongs to this salon
        if ($request->employee_id) {
            $employee = \App\Models\User::find($request->employee_id);
            if ($employee->salon_id !== $salon->id || !$employee->hasAnyRole(['manager', 'employee'])) {
                return back()->withErrors(['employee_id' => 'Invalid employee selected.']);
            }
        }

        // Handle photo uploads
        $photos = [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('reviews', 'public');
                $photos[] = $path;
            }
        }

        $review = $salon->reviews()->create([
            'customer_id' => $request->customer_id,
            'service_id' => $request->service_id,
            'employee_id' => $request->employee_id,
            'rating' => $request->rating,
            'title' => $request->title,
            'review' => $request->review,
            'photos' => $photos,
            'is_approved' => true, // Auto-approve when created by admin
            'is_featured' => $request->is_featured ?? false,
            'source' => 'admin',
        ]);

        return redirect()->route('salon.reviews.index')
            ->with('success', 'Review created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Review $review)
    {
        // Ensure the review belongs to the current salon
        if ($review->salon_id !== Auth::user()->salon_id) {
            abort(403);
        }

        $review->load(['customer', 'service', 'employee', 'salon']);

        return view('salon.reviews.show', compact('review'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Review $review)
    {
        // Ensure the review belongs to the current salon
        if ($review->salon_id !== Auth::user()->salon_id) {
            abort(403);
        }

        $salon = Auth::user()->salon;
        $customers = $salon->users()
            ->whereHas('roles', function($q) {
                $q->where('name', 'customer');
            })
            ->orderBy('name')
            ->get();

        $services = $salon->services()->orderBy('name')->get();
        $employees = $salon->users()
            ->whereHas('roles', function($q) {
                $q->whereIn('name', ['manager', 'employee']);
            })
            ->orderBy('name')
            ->get();

        return view('salon.reviews.edit', compact('review', 'salon', 'customers', 'services', 'employees'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Review $review)
    {
        // Ensure the review belongs to the current salon
        if ($review->salon_id !== Auth::user()->salon_id) {
            abort(403);
        }

        $request->validate([
            'customer_id' => 'required|exists:users,id',
            'service_id' => 'nullable|exists:services,id',
            'employee_id' => 'nullable|exists:users,id',
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'nullable|string|max:255',
            'review' => 'required|string|max:1000',
            'photos' => 'nullable|array|max:5',
            'photos.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_approved' => 'boolean',
            'is_featured' => 'boolean',
        ]);

        // Check if customer belongs to this salon
        $customer = \App\Models\User::find($request->customer_id);
        if ($customer->salon_id !== $review->salon_id || !$customer->hasRole('customer')) {
            return back()->withErrors(['customer_id' => 'Invalid customer selected.']);
        }

        // Check if service belongs to this salon
        if ($request->service_id) {
            $service = \App\Models\Service::find($request->service_id);
            if ($service->salon_id !== $review->salon_id) {
                return back()->withErrors(['service_id' => 'Invalid service selected.']);
            }
        }

        // Check if employee belongs to this salon
        if ($request->employee_id) {
            $employee = \App\Models\User::find($request->employee_id);
            if ($employee->salon_id !== $review->salon_id || !$employee->hasAnyRole(['manager', 'employee'])) {
                return back()->withErrors(['employee_id' => 'Invalid employee selected.']);
            }
        }

        // Handle photo uploads
        $photos = $review->photos ?? [];
        if ($request->hasFile('photos')) {
            // Delete old photos
            foreach ($photos as $photo) {
                Storage::disk('public')->delete($photo);
            }
            
            // Upload new photos
            $photos = [];
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('reviews', 'public');
                $photos[] = $path;
            }
        }

        $review->update([
            'customer_id' => $request->customer_id,
            'service_id' => $request->service_id,
            'employee_id' => $request->employee_id,
            'rating' => $request->rating,
            'title' => $request->title,
            'review' => $request->review,
            'photos' => $photos,
            'is_approved' => $request->is_approved ?? $review->is_approved,
            'is_featured' => $request->is_featured ?? $review->is_featured,
        ]);

        return redirect()->route('salon.reviews.index')
            ->with('success', 'Review updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Review $review)
    {
        // Ensure the review belongs to the current salon
        if ($review->salon_id !== Auth::user()->salon_id) {
            abort(403);
        }

        // Delete photos
        if ($review->photos) {
            foreach ($review->photos as $photo) {
                Storage::disk('public')->delete($photo);
            }
        }

        $review->delete();

        return redirect()->route('salon.reviews.index')
            ->with('success', 'Review deleted successfully.');
    }

    /**
     * Approve a review
     */
    public function approve(Review $review)
    {
        // Ensure the review belongs to the current salon
        if ($review->salon_id !== Auth::user()->salon_id) {
            abort(403);
        }

        $review->update(['is_approved' => true]);

        return response()->json(['success' => true, 'message' => 'Review approved successfully.']);
    }

    /**
     * Feature a review
     */
    public function feature(Review $review)
    {
        // Ensure the review belongs to the current salon
        if ($review->salon_id !== Auth::user()->salon_id) {
            abort(403);
        }

        $review->update(['is_featured' => !$review->is_featured]);

        return response()->json([
            'success' => true, 
            'message' => $review->is_featured ? 'Review featured successfully.' : 'Review unfeatured successfully.',
            'is_featured' => $review->is_featured
        ]);
    }

    /**
     * Get salon review statistics
     */
    public function stats(Request $request)
    {
        $salon = Auth::user()->salon;
        
        if (!$salon) {
            return response()->json(['success' => false, 'message' => 'Salon not found.']);
        }

        $totalReviews = $salon->reviews()->count();
        $approvedReviews = $salon->reviews()->where('is_approved', true)->count();
        $pendingReviews = $salon->reviews()->where('is_approved', false)->count();
        $featuredReviews = $salon->reviews()->where('is_featured', true)->count();
        
        $averageRating = $salon->reviews()->where('is_approved', true)->avg('rating');
        
        $ratingDistribution = [
            5 => $salon->reviews()->where('rating', 5)->where('is_approved', true)->count(),
            4 => $salon->reviews()->where('rating', 4)->where('is_approved', true)->count(),
            3 => $salon->reviews()->where('rating', 3)->where('is_approved', true)->count(),
            2 => $salon->reviews()->where('rating', 2)->where('is_approved', true)->count(),
            1 => $salon->reviews()->where('rating', 1)->where('is_approved', true)->count(),
        ];

        return response()->json([
            'success' => true,
            'stats' => [
                'total_reviews' => $totalReviews,
                'approved_reviews' => $approvedReviews,
                'pending_reviews' => $pendingReviews,
                'featured_reviews' => $featuredReviews,
                'average_rating' => round($averageRating, 1),
                'rating_distribution' => $ratingDistribution,
            ]
        ]);
    }
}
