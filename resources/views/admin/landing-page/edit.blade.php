@extends('layouts.modern')

@section('title', 'Edit Landing Page Section')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <a href="{{ route('admin.landing-page.index') }}" class="btn btn-outline-secondary mb-3">
                <i class="fas fa-arrow-left me-2"></i> Back to Sections
            </a>
            <h2>
                <i class="fas fa-edit me-2"></i>
                Edit {{ ucwords(str_replace('_', ' ', $section->section_key)) }} Section
            </h2>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <form action="{{ route('admin.landing-page.update', $section->id) }}" 
                          method="POST" 
                          enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Title -->
                        <div class="mb-4">
                            <label for="title" class="form-label fw-bold">Section Title</label>
                            <input type="text" 
                                   class="form-control @error('title') is-invalid @enderror" 
                                   id="title" 
                                   name="title" 
                                   value="{{ old('title', $section->title) }}"
                                   placeholder="Enter section title">
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Main heading for this section</small>
                        </div>

                        <!-- Content -->
                        <div class="mb-4">
                            <label for="content" class="form-label fw-bold">Content</label>
                            <textarea class="form-control @error('content') is-invalid @enderror" 
                                      id="content" 
                                      name="content" 
                                      rows="5"
                                      placeholder="Enter section content">{{ old('content', $section->content) }}</textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Description or subtitle</small>
                        </div>

                        <!-- Additional Data (JSON) -->
                        @if($section->data)
                            <div class="mb-4">
                                <label for="data" class="form-label fw-bold">Additional Data (JSON)</label>
                                <textarea class="form-control @error('data') is-invalid @enderror" 
                                          id="data" 
                                          name="data" 
                                          rows="10"
                                          placeholder='{"key": "value"}'>{{ old('data', json_encode($section->data, JSON_PRETTY_PRINT)) }}</textarea>
                                @error('data')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Complex data in JSON format (for features, pricing, etc.)</small>
                            </div>
                        @endif

                        <!-- Image Upload -->
                        <div class="mb-4">
                            <label for="image" class="form-label fw-bold">Section Image</label>
                            @if($section->image)
                                <div class="mb-3">
                                    <img src="{{ asset('storage/' . $section->image) }}" 
                                         alt="Current image" 
                                         class="img-thumbnail" 
                                         style="max-height: 200px;">
                                    <p class="text-muted small mt-2">Current image</p>
                                </div>
                            @endif
                            <input type="file" 
                                   class="form-control @error('image') is-invalid @enderror" 
                                   id="image" 
                                   name="image"
                                   accept="image/*">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Upload a new image (max 2MB)</small>
                        </div>

                        <!-- Status -->
                        <div class="mb-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       id="is_active" 
                                       name="is_active" 
                                       value="1"
                                       {{ old('is_active', $section->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold" for="is_active">
                                    Active
                                </label>
                            </div>
                            <small class="text-muted">Show this section on the landing page</small>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>
                                Save Changes
                            </button>
                            <a href="{{ route('admin.landing-page.index') }}" class="btn btn-outline-secondary">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Preview/Info Sidebar -->
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Section Information</h6>
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-5">Section Key:</dt>
                        <dd class="col-sm-7"><code>{{ $section->section_key }}</code></dd>

                        <dt class="col-sm-5">Status:</dt>
                        <dd class="col-sm-7">
                            @if($section->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </dd>

                        <dt class="col-sm-5">Created:</dt>
                        <dd class="col-sm-7">{{ $section->created_at->format('M d, Y') }}</dd>

                        <dt class="col-sm-5">Last Updated:</dt>
                        <dd class="col-sm-7">{{ $section->updated_at->diffForHumans() }}</dd>
                    </dl>
                </div>
            </div>

            <div class="card shadow-sm mt-3">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Tips</h6>
                </div>
                <div class="card-body">
                    <ul class="mb-0 ps-3">
                        <li class="mb-2">Keep titles concise and compelling</li>
                        <li class="mb-2">Use clear, benefit-focused content</li>
                        <li class="mb-2">Optimize images for web (recommended: under 500KB)</li>
                        <li>Preview changes on the live site after saving</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
