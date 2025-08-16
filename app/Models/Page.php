<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Page extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'template',
        'status',
        'featured_image',
        'is_homepage',
        'is_footer',
        'sort_order',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'published_at',
        'views',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'views' => 'integer',
        'is_homepage' => 'boolean',
        'is_footer' => 'boolean',
        'sort_order' => 'integer',
    ];

    protected $dates = [
        'published_at',
    ];

    /**
     * Get the author that owns the page
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Scope a query to only include published pages
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    /**
     * Scope a query to only include draft pages
     */
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    /**
     * Scope a query to only include archived pages
     */
    public function scopeArchived($query)
    {
        return $query->where('status', 'archived');
    }

    /**
     * Scope a query to only include homepage
     */
    public function scopeHomepage($query)
    {
        return $query->where('is_homepage', true);
    }

    /**
     * Scope a query to only include footer pages
     */
    public function scopeFooter($query)
    {
        return $query->where('is_footer', true);
    }

    /**
     * Scope a query to order by sort order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc');
    }

    /**
     * Get the page's URL
     */
    public function getUrlAttribute()
    {
        return route('page.show', $this->slug);
    }

    /**
     * Increment the view count
     */
    public function incrementViews()
    {
        $this->increment('views');
    }

    /**
     * Check if the page is published
     */
    public function isPublished(): bool
    {
        return $this->status === 'published';
    }

    /**
     * Check if the page is draft
     */
    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    /**
     * Check if the page is archived
     */
    public function isArchived(): bool
    {
        return $this->status === 'archived';
    }

    /**
     * Check if the page is homepage
     */
    public function isHomepage(): bool
    {
        return $this->is_homepage;
    }

    /**
     * Check if the page is footer
     */
    public function isFooter(): bool
    {
        return $this->is_footer;
    }

    /**
     * Get the page's status badge class
     */
    public function getStatusBadgeClassAttribute(): string
    {
        return match($this->status) {
            'published' => 'badge-success',
            'draft' => 'badge-warning',
            'archived' => 'badge-secondary',
            default => 'badge-light',
        };
    }

    /**
     * Get the page's status display name
     */
    public function getStatusDisplayNameAttribute(): string
    {
        return ucfirst($this->status);
    }

    /**
     * Get the page's template display name
     */
    public function getTemplateDisplayNameAttribute(): string
    {
        return ucfirst(str_replace('-', ' ', $this->template));
    }

    /**
     * Set this page as homepage
     */
    public function setAsHomepage(): void
    {
        // Remove homepage from other pages
        static::where('is_homepage', true)->update(['is_homepage' => false]);
        
        // Set this page as homepage
        $this->update(['is_homepage' => true]);
    }

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($page) {
            if (empty($page->slug)) {
                $page->slug = Str::slug($page->title);
            }
        });

        static::updating(function ($page) {
            if ($page->isDirty('title') && empty($page->slug)) {
                $page->slug = Str::slug($page->title);
            }
        });
    }
}
