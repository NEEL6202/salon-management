<?php

namespace App\Http\Controllers;

use App\Models\Salon;
use App\Models\User;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class SalonController extends Controller
{
    public function index()
    {
        $salons = Salon::with(['subscriptionPlan', 'users'])->paginate(15);
        return view('admin.salons.index', compact('salons'));
    }

    public function show(Salon $salon)
    {
        $salon->load(['users', 'services', 'products', 'appointments', 'orders', 'subscriptionPlan']);
        return view('admin.salons.show', compact('salon'));
    }

    public function create()
    {
        $subscriptionPlans = SubscriptionPlan::where('is_active', true)->get();
        return view('admin.salons.create', compact('subscriptionPlans'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:salons,email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'website' => 'nullable|url',
            'subscription_plan_id' => 'nullable|exists:subscription_plans,id',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'banner' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $request->all();
        $data['slug'] = Str::slug($request->name);
        $data['status'] = 'active';

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('salons/logos', 'public');
        }

        // Handle banner upload
        if ($request->hasFile('banner')) {
            $data['banner'] = $request->file('banner')->store('salons/banners', 'public');
        }

        $salon = Salon::create($data);

        return redirect()->route('admin.salons.index')->with('success', 'Salon created successfully.');
    }

    public function edit(Salon $salon)
    {
        $subscriptionPlans = SubscriptionPlan::where('is_active', true)->get();
        return view('admin.salons.edit', compact('salon', 'subscriptionPlans'));
    }

    public function update(Request $request, Salon $salon)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:salons,email,' . $salon->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'website' => 'nullable|url',
            'subscription_plan_id' => 'nullable|exists:subscription_plans,id',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'banner' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $request->all();
        $data['slug'] = Str::slug($request->name);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            if ($salon->logo) {
                Storage::disk('public')->delete($salon->logo);
            }
            $data['logo'] = $request->file('logo')->store('salons/logos', 'public');
        }

        // Handle banner upload
        if ($request->hasFile('banner')) {
            if ($salon->banner) {
                Storage::disk('public')->delete($salon->banner);
            }
            $data['banner'] = $request->file('banner')->store('salons/banners', 'public');
        }

        $salon->update($data);

        return redirect()->route('admin.salons.index')->with('success', 'Salon updated successfully.');
    }

    public function destroy(Salon $salon)
    {
        // Delete associated files
        if ($salon->logo) {
            Storage::disk('public')->delete($salon->logo);
        }
        if ($salon->banner) {
            Storage::disk('public')->delete($salon->banner);
        }

        $salon->delete();

        return redirect()->route('admin.salons.index')->with('success', 'Salon deleted successfully.');
    }

    public function profile()
    {
        $salon = Auth::user()->salon;
        return view('salon.profile', compact('salon'));
    }

    public function updateProfile(Request $request)
    {
        $salon = Auth::user()->salon;
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'website' => 'nullable|url',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'banner' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $request->all();

        // Handle logo upload
        if ($request->hasFile('logo')) {
            if ($salon->logo) {
                Storage::disk('public')->delete($salon->logo);
            }
            $data['logo'] = $request->file('logo')->store('salons/logos', 'public');
        }

        // Handle banner upload
        if ($request->hasFile('banner')) {
            if ($salon->banner) {
                Storage::disk('public')->delete($salon->banner);
            }
            $data['banner'] = $request->file('banner')->store('salons/banners', 'public');
        }

        $salon->update($data);

        return redirect()->route('salon.profile')->with('success', 'Profile updated successfully.');
    }
} 