<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Blog;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AdminBlogController extends Controller
{
    public function __construct()
    {
        // Middleware is applied at the route level
    }

    /**
     * Display a listing of blogs with advanced filtering and search
     */
    public function index(Request $request)
    {
        $query = Blog::with(['category', 'author']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%");
            });
        }

        // Category filtering
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Status filtering
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Author filtering
        if ($request->filled('author')) {
            $query->where('author_id', $request->author);
        }

        // Date range filtering
        if ($request->filled('date_from')) {
            $query->where('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('created_at', '<=', $request->date_to . ' 23:59:59');
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $blogs = $query->paginate(20)->withQueryString();

        // Get available filters
        $categories = Category::all();
        $statuses = ['draft', 'published', 'archived', 'pending_review'];
        $authors = \App\Models\User::whereHas('roles', function($q) {
            $q->whereIn('name', ['admin', 'super_admin', 'content_manager']);
        })->get();

        // Statistics
        $stats = [
            'total_blogs' => Blog::count(),
            'published_blogs' => Blog::where('status', 'published')->count(),
            'draft_blogs' => Blog::where('status', 'draft')->count(),
            'archived_blogs' => Blog::where('status', 'archived')->count(),
            'blogs_this_month' => Blog::whereMonth('created_at', now()->month)->count(),
            'total_views' => Blog::sum('views'),
        ];

        return view('admin.blogs.index', compact('blogs', 'categories', 'statuses', 'authors', 'stats'));
    }

    /**
     * Show the form for creating a new blog
     */
    public function create()
    {
        $categories = Category::all();
        $statuses = ['draft', 'published', 'pending_review'];
        
        return view('admin.blogs.create', compact('categories', 'statuses'));
    }

    /**
     * Store a newly created blog
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
            'category_id' => 'nullable|exists:categories,id',
            'status' => 'required|in:draft,published,archived,pending_review',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'meta_title' => 'nullable|string|max:60',
            'meta_description' => 'nullable|string|max:160',
            'tags' => 'nullable|string',
            'published_at' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $blogData = $request->only([
            'title', 'excerpt', 'content', 'category_id', 'status', 
            'meta_title', 'meta_description', 'tags'
        ]);

        // Generate slug
        $blogData['slug'] = Str::slug($request->title);
        
        // Set author
        $blogData['author_id'] = auth()->id();
        
        // Set published_at if status is published
        if ($request->status === 'published' && !$request->filled('published_at')) {
            $blogData['published_at'] = now();
        } elseif ($request->filled('published_at')) {
            $blogData['published_at'] = $request->published_at;
        }

        // Handle featured image
        if ($request->hasFile('featured_image')) {
            $imagePath = $request->file('featured_image')->store('blogs', 'public');
            $blogData['featured_image'] = asset('storage/' . $imagePath);
        }

        $blog = Blog::create($blogData);

        return redirect()->route('admin.blogs.index')
            ->with('success', 'Blog post created successfully.');
    }

    /**
     * Display the specified blog
     */
    public function show(Blog $blog)
    {
        $blog->load(['category', 'author']);
        
        return view('admin.blogs.show', compact('blog'));
    }

    /**
     * Show the form for editing the specified blog
     */
    public function edit(Blog $blog)
    {
        $categories = Category::all();
        $statuses = ['draft', 'published', 'archived', 'pending_review'];
        
        return view('admin.blogs.edit', compact('blog', 'categories', 'statuses'));
    }

    /**
     * Update the specified blog
     */
    public function update(Request $request, Blog $blog)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
            'category_id' => 'nullable|exists:categories,id',
            'status' => 'required|in:draft,published,archived,pending_review',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'meta_title' => 'nullable|string|max:60',
            'meta_description' => 'nullable|string|max:160',
            'tags' => 'nullable|string',
            'published_at' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $blogData = $request->only([
            'title', 'excerpt', 'content', 'category_id', 'status', 
            'meta_title', 'meta_description', 'tags'
        ]);

        // Generate new slug if title changed
        if ($request->title !== $blog->title) {
            $blogData['slug'] = Str::slug($request->title);
        }
        
        // Set published_at if status is published
        if ($request->status === 'published' && !$request->filled('published_at')) {
            $blogData['published_at'] = now();
        } elseif ($request->filled('published_at')) {
            $blogData['published_at'] = $request->published_at;
        }

        // Handle featured image
        if ($request->hasFile('featured_image')) {
            // Delete old image
            if ($blog->featured_image) {
                $oldPath = str_replace(asset('storage/'), '', $blog->featured_image);
                Storage::disk('public')->delete($oldPath);
            }
            
            $imagePath = $request->file('featured_image')->store('blogs', 'public');
            $blogData['featured_image'] = asset('storage/' . $imagePath);
        }

        $blog->update($blogData);

        return redirect()->route('admin.blogs.index')
            ->with('success', 'Blog post updated successfully.');
    }

    /**
     * Remove the specified blog
     */
    public function destroy(Blog $blog)
    {
        // Delete featured image
        if ($blog->featured_image) {
            $imagePath = str_replace(asset('storage/'), '', $blog->featured_image);
            Storage::disk('public')->delete($imagePath);
        }

        $blog->delete();

        return redirect()->route('admin.blogs.index')
            ->with('success', 'Blog post deleted successfully.');
    }

    /**
     * Bulk actions on blogs
     */
    public function bulkAction(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'action' => 'required|in:publish,unpublish,archive,delete,change_category',
            'blog_ids' => 'required|array|min:1',
            'blog_ids.*' => 'exists:blogs,id',
            'new_category_id' => 'required_if:action,change_category|exists:categories,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator);
        }

        $blogs = Blog::whereIn('id', $request->blog_ids);

        switch ($request->action) {
            case 'publish':
                $blogs->update(['status' => 'published', 'published_at' => now()]);
                $message = 'Blog posts published successfully.';
                break;

            case 'unpublish':
                $blogs->update(['status' => 'draft']);
                $message = 'Blog posts unpublished successfully.';
                break;

            case 'archive':
                $blogs->update(['status' => 'archived']);
                $message = 'Blog posts archived successfully.';
                break;

            case 'delete':
                $blogs->delete();
                $message = 'Blog posts deleted successfully.';
                break;

            case 'change_category':
                $blogs->update(['category_id' => $request->new_category_id]);
                $message = 'Blog post categories updated successfully.';
                break;
        }

        return redirect()->route('admin.blogs.index')
            ->with('success', $message);
    }

    /**
     * Toggle blog status
     */
    public function toggleStatus(Blog $blog)
    {
        $newStatus = $blog->status === 'published' ? 'draft' : 'published';
        $blog->update([
            'status' => $newStatus,
            'published_at' => $newStatus === 'published' ? now() : null
        ]);

        return response()->json([
            'success' => true,
            'message' => "Blog status changed to {$newStatus}",
            'new_status' => $newStatus
        ]);
    }

    /**
     * Get blog statistics
     */
    public function getStats()
    {
        $stats = [
            'total_blogs' => Blog::count(),
            'published_blogs' => Blog::where('status', 'published')->count(),
            'draft_blogs' => Blog::where('status', 'draft')->count(),
            'archived_blogs' => Blog::where('status', 'archived')->count(),
            'pending_review' => Blog::where('status', 'pending_review')->count(),
            'blogs_this_month' => Blog::whereMonth('created_at', now()->month)->count(),
            'total_views' => Blog::sum('views'),
            'top_blogs' => Blog::orderBy('views', 'desc')->take(5)->get(['id', 'title', 'views']),
        ];

        return response()->json($stats);
    }

    /**
     * Export blogs data
     */
    public function export(Request $request)
    {
        $format = $request->get('format', 'csv');
        
        $blogs = Blog::with(['category', 'author'])->get();
        
        if ($format === 'json') {
            return response()->json($blogs);
        }

        // For CSV export, you would implement CSV generation logic here
        return response()->json(['message' => 'Export functionality to be implemented']);
    }
}
