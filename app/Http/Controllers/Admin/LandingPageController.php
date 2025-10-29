<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LandingPageSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LandingPageController extends Controller
{
    public function index()
    {
        $sections = LandingPageSection::ordered()->get();
        return view('admin.landing-page.index', compact('sections'));
    }

    public function edit($id)
    {
        $section = LandingPageSection::findOrFail($id);
        return view('admin.landing-page.edit', compact('section'));
    }

    public function update(Request $request, $id)
    {
        $section = LandingPageSection::findOrFail($id);
        
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'content' => 'nullable|string',
            'data' => 'nullable|json',
            'image' => 'nullable|image|max:2048',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image
            if ($section->image) {
                Storage::disk('public')->delete($section->image);
            }
            
            $validated['image'] = $request->file('image')->store('landing-page', 'public');
        }

        $section->update($validated);

        return redirect()->route('admin.landing-page.index')
            ->with('success', 'Section updated successfully!');
    }

    public function toggleStatus($id)
    {
        $section = LandingPageSection::findOrFail($id);
        $section->update(['is_active' => !$section->is_active]);

        return redirect()->back()
            ->with('success', 'Section status updated!');
    }

    public function initialize()
    {
        $defaultSections = [
            [
                'section_key' => 'hero',
                'title' => 'Transform Your Salon Business',
                'content' => 'All-in-one salon management platform with smart booking, inventory control, and powerful analytics to grow your business.',
                'is_active' => true,
                'order' => 1,
            ],
            [
                'section_key' => 'stats',
                'title' => 'Trusted by Professionals',
                'data' => [
                    ['label' => 'Active Salons', 'value' => '10,000+', 'icon' => 'fa-store'],
                    ['label' => 'Appointments Monthly', 'value' => '50,000+', 'icon' => 'fa-calendar-check'],
                    ['label' => 'Happy Customers', 'value' => '100,000+', 'icon' => 'fa-users'],
                    ['label' => 'Countries Worldwide', 'value' => '25+', 'icon' => 'fa-globe'],
                ],
                'is_active' => true,
                'order' => 2,
            ],
            [
                'section_key' => 'features',
                'title' => 'Everything You Need to Succeed',
                'data' => [
                    [
                        'icon' => 'fa-calendar-alt',
                        'title' => 'Smart Booking System',
                        'description' => 'Online booking 24/7 with automated reminders and calendar sync.',
                    ],
                    [
                        'icon' => 'fa-users',
                        'title' => 'Client Management',
                        'description' => 'Complete customer profiles with history, preferences, and notes.',
                    ],
                    [
                        'icon' => 'fa-chart-line',
                        'title' => 'Business Analytics',
                        'description' => 'Real-time insights into revenue, bookings, and performance.',
                    ],
                    [
                        'icon' => 'fa-box',
                        'title' => 'Inventory Management',
                        'description' => 'Track products, supplies, and automate reordering.',
                    ],
                    [
                        'icon' => 'fa-money-bill-wave',
                        'title' => 'POS & Payments',
                        'description' => 'Accept payments, track sales, and manage invoices.',
                    ],
                    [
                        'icon' => 'fa-mobile-alt',
                        'title' => 'Mobile Ready',
                        'description' => 'Manage your salon on-the-go with mobile app access.',
                    ],
                ],
                'is_active' => true,
                'order' => 3,
            ],
            [
                'section_key' => 'pricing',
                'title' => 'Simple, Transparent Pricing',
                'data' => [
                    [
                        'name' => 'Starter',
                        'price' => '$29',
                        'period' => 'per month',
                        'features' => [
                            'Up to 100 appointments/month',
                            'Basic reporting',
                            'Email support',
                            '1 staff member',
                        ],
                        'highlighted' => false,
                    ],
                    [
                        'name' => 'Professional',
                        'price' => '$79',
                        'period' => 'per month',
                        'features' => [
                            'Unlimited appointments',
                            'Advanced analytics',
                            'Priority support',
                            'Up to 10 staff members',
                            'Inventory management',
                            'Online booking',
                        ],
                        'highlighted' => true,
                    ],
                    [
                        'name' => 'Enterprise',
                        'price' => '$199',
                        'period' => 'per month',
                        'features' => [
                            'Everything in Professional',
                            'Unlimited staff',
                            'Multiple locations',
                            'Custom integrations',
                            'Dedicated support',
                            'White-label option',
                        ],
                        'highlighted' => false,
                    ],
                ],
                'is_active' => true,
                'order' => 4,
            ],
        ];

        foreach ($defaultSections as $sectionData) {
            LandingPageSection::updateOrCreate(
                ['section_key' => $sectionData['section_key']],
                $sectionData
            );
        }

        return response()->json(['success' => true, 'message' => 'Sections initialized successfully!']);
    }
}
