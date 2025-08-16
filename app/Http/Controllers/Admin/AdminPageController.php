<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Page;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AdminPageController extends Controller
{
    public function __construct()
    {
        // Middleware is applied at the route level
    }

    /**
     * Display a listing of pages with advanced filtering and search
     */
    public function index(Request $request)
    {
        $query = Page::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        // Status filtering
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Template filtering
        if ($request->filled('template')) {
            $query->where('template', $request->template);
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

        $pages = $query->paginate(20)->withQueryString();

        // Get available filters
        $statuses = ['draft', 'published', 'archived'];
        $templates = ['default', 'full-width', 'sidebar', 'landing', 'contact', 'about'];

        // Statistics
        $stats = [
            'total_pages' => Page::count(),
            'published_pages' => Page::where('status', 'published')->count(),
            'draft_pages' => Page::where('status', 'draft')->count(),
            'archived_pages' => Page::where('status', 'archived')->count(),
            'pages_this_month' => Page::whereMonth('created_at', now()->month)->count(),
            'total_views' => Page::sum('views'),
        ];

        return view('admin.pages.index', compact('pages', 'statuses', 'templates', 'stats'));
    }

    /**
     * Show the form for creating a new page
     */
    public function create()
    {
        $templates = ['default', 'full-width', 'sidebar', 'landing', 'contact', 'about'];
        $statuses = ['draft', 'published'];
        
        return view('admin.pages.create', compact('templates', 'statuses'));
    }

    /**
     * Store a newly created page
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:pages',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
            'template' => 'required|in:default,full-width,sidebar,landing,contact,about',
            'status' => 'required|in:draft,published,archived',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'meta_title' => 'nullable|string|max:60',
            'meta_description' => 'nullable|string|max:160',
            'meta_keywords' => 'nullable|string',
            'is_homepage' => 'boolean',
            'is_footer' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
            'published_at' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $pageData = $request->only([
            'title', 'excerpt', 'content', 'template', 'status', 
            'meta_title', 'meta_description', 'meta_keywords',
            'is_homepage', 'is_footer', 'sort_order'
        ]);

        // Generate slug if not provided
        if (!$request->filled('slug')) {
            $pageData['slug'] = Str::slug($request->title);
        }
        
        // Set published_at if status is published
        if ($request->status === 'published' && !$request->filled('published_at')) {
            $pageData['published_at'] = now();
        } elseif ($request->filled('published_at')) {
            $pageData['published_at'] = $request->published_at;
        }

        // Handle featured image
        if ($request->hasFile('featured_image')) {
            $imagePath = $request->file('featured_image')->store('pages', 'public');
            $pageData['featured_image'] = asset('storage/' . $imagePath);
        }

        // If this is set as homepage, unset other pages as homepage
        if ($request->boolean('is_homepage')) {
            Page::where('is_homepage', true)->update(['is_homepage' => false]);
        }

        $page = Page::create($pageData);

        return redirect()->route('admin.pages.index')
            ->with('success', 'Page created successfully.');
    }

    /**
     * Display the specified page
     */
    public function show(Page $page)
    {
        return view('admin.pages.show', compact('page'));
    }

    /**
     * Show the form for editing the specified page
     */
    public function edit(Page $page)
    {
        $templates = ['default', 'full-width', 'sidebar', 'landing', 'contact', 'about'];
        $statuses = ['draft', 'published', 'archived'];
        
        return view('admin.pages.edit', compact('page', 'templates', 'statuses'));
    }

    /**
     * Update the specified page
     */
    public function update(Request $request, Page $page)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'slug' => ['nullable', 'string', 'max:255', \Illuminate\Validation\Rule::unique('pages')->ignore($page->id)],
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
            'template' => 'required|in:default,full-width,sidebar,landing,contact,about',
            'status' => 'required|in:draft,published,archived',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'meta_title' => 'nullable|string|max:60',
            'meta_description' => 'nullable|string|max:160',
            'meta_keywords' => 'nullable|string',
            'is_homepage' => 'boolean',
            'is_footer' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
            'published_at' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $pageData = $request->only([
            'title', 'excerpt', 'content', 'template', 'status', 
            'meta_title', 'meta_description', 'meta_keywords',
            'is_homepage', 'is_footer', 'sort_order'
        ]);

        // Generate new slug if not provided and title changed
        if (!$request->filled('slug') && $request->title !== $page->title) {
            $pageData['slug'] = Str::slug($request->title);
        }
        
        // Set published_at if status is published
        if ($request->status === 'published' && !$request->filled('published_at')) {
            $pageData['published_at'] = now();
        } elseif ($request->filled('published_at')) {
            $pageData['published_at'] = $request->published_at;
        }

        // Handle featured image
        if ($request->hasFile('featured_image')) {
            // Delete old image
            if ($page->featured_image) {
                $oldPath = str_replace(asset('storage/'), '', $page->featured_image);
                Storage::disk('public')->delete($oldPath);
            }
            
            $imagePath = $request->file('featured_image')->store('pages', 'public');
            $pageData['featured_image'] = asset('storage/' . $imagePath);
        }

        // If this is set as homepage, unset other pages as homepage
        if ($request->boolean('is_homepage')) {
            Page::where('is_homepage', true)->where('id', '!=', $page->id)->update(['is_homepage' => false]);
        }

        $page->update($pageData);

        return redirect()->route('admin.pages.index')
            ->with('success', 'Page updated successfully.');
    }

    /**
     * Remove the specified page
     */
    public function destroy(Page $page)
    {
        // Prevent deletion of homepage
        if ($page->is_homepage) {
            return redirect()->route('admin.pages.index')
                ->with('error', 'Cannot delete the homepage.');
        }

        // Delete featured image
        if ($page->featured_image) {
            $imagePath = str_replace(asset('storage/'), '', $page->featured_image);
            Storage::disk('public')->delete($imagePath);
        }

        $page->delete();

        return redirect()->route('admin.pages.index')
            ->with('success', 'Page deleted successfully.');
    }

    /**
     * Bulk actions on pages
     */
    public function bulkAction(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'action' => 'required|in:publish,unpublish,archive,delete,change_template',
            'page_ids' => 'required|array|min:1',
            'page_ids.*' => 'exists:pages,id',
            'new_template' => 'required_if:action,change_template|in:default,full-width,sidebar,landing,contact,about',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator);
        }

        $pages = Page::whereIn('id', $request->page_ids);

        switch ($request->action) {
            case 'publish':
                $pages->update(['status' => 'published', 'published_at' => now()]);
                $message = 'Pages published successfully.';
                break;

            case 'unpublish':
                $pages->update(['status' => 'draft']);
                $message = 'Pages unpublished successfully.';
                break;

            case 'archive':
                $pages->update(['status' => 'archived']);
                $message = 'Pages archived successfully.';
                break;

            case 'delete':
                $pages->delete();
                $message = 'Pages deleted successfully.';
                break;

            case 'change_template':
                $pages->update(['template' => $request->new_template]);
                $message = 'Page templates updated successfully.';
                break;
        }

        return redirect()->route('admin.pages.index')
            ->with('success', $message);
    }

    /**
     * Toggle page status
     */
    public function toggleStatus(Page $page)
    {
        $newStatus = $page->status === 'published' ? 'draft' : 'published';
        $page->update([
            'status' => $newStatus,
            'published_at' => $newStatus === 'published' ? now() : null
        ]);

        return response()->json([
            'success' => true,
            'message' => "Page status changed to {$newStatus}",
            'new_status' => $newStatus
        ]);
    }

    /**
     * Set page as homepage
     */
    public function setHomepage(Page $page)
    {
        // Unset current homepage
        Page::where('is_homepage', true)->update(['is_homepage' => false]);
        
        // Set new homepage
        $page->update(['is_homepage' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Homepage updated successfully.'
        ]);
    }

    /**
     * Get page statistics
     */
    public function getStats()
    {
        $stats = [
            'total_pages' => Page::count(),
            'published_pages' => Page::where('status', 'published')->count(),
            'draft_pages' => Page::where('status', 'draft')->count(),
            'archived_pages' => Page::where('status', 'archived')->count(),
            'pages_this_month' => Page::whereMonth('created_at', now()->month)->count(),
            'total_views' => Page::sum('views'),
            'homepage' => Page::where('is_homepage', true)->first(['id', 'title']),
            'footer_pages' => Page::where('is_footer', true)->count(),
        ];

        return response()->json($stats);
    }

    /**
     * Export pages data
     */
    public function export(Request $request)
    {
        $format = $request->get('format', 'csv');
        
        $pages = Page::all();
        
        if ($format === 'json') {
            return response()->json($pages);
        }

        // For CSV export, you would implement CSV generation logic here
        return response()->json(['message' => 'Export functionality to be implemented']);
    }

    /**
     * Preview page
     */
    public function preview(Page $page)
    {
        return view('admin.pages.preview', compact('page'));
    }

    /**
     * Duplicate page
     */
    public function duplicate(Page $page)
    {
        $newPage = $page->replicate();
        $newPage->title = $page->title . ' (Copy)';
        $newPage->slug = Str::slug($page->title . '-copy-' . time());
        $newPage->status = 'draft';
        $newPage->is_homepage = false;
        $newPage->views = 0;
        $newPage->save();

        return redirect()->route('admin.pages.edit', $newPage)
            ->with('success', 'Page duplicated successfully. You can now edit the copy.');
    }
}
