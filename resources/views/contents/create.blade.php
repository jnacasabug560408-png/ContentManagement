@extends('layouts.app')

@section('title', 'Create Post')
@section('page-title', 'Create New Post')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8"> {{-- Ginawa nating mas malapad (10) para sa better writing experience --}}
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Create New Post</h5>
                </div>
                <div class="card-body">
                    {{-- Form starts here --}}
                    <form action="{{ route('contents.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        {{-- TITLE --}}
                        <div class="mb-4">
                            <label for="title" class="form-label fw-bold">Title</label>
                            <input type="text" class="form-control form-control-lg @error('title') is-invalid @enderror"
                                   id="title" name="title" value="{{ old('title') }}" 
                                   placeholder="Enter a catchy title..." required>
                            @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="row">
                            {{-- CATEGORY DROPDOWN --}}
                            <div class="col-md-6 mb-4">
                                <label for="category_id" class="form-label fw-bold">Category</label>
                                <select class="form-select" id="category_id" name="category_id" required>
    <option value="" selected disabled>Select Category</option>
    
    {{-- Ito ang mag-lo-loop sa lahat ng in-insert mo sa SQL --}}
    @foreach($categories as $category)
        <option value="{{ $category->id }}">{{ $category->name }}</option>
    @endforeach
</select>

                            {{-- STATUS --}}
                            <div class="col-md-6 mb-4">
                                <label for="status" class="form-label fw-bold">Status</label>
                                <select class="form-select @error('status') is-invalid @enderror"
                                        id="status" name="status" required>
                                    {{-- logic: Ang creator ay draft lang dapat, pero if admin, can publish --}}
                                    <option value="draft" @selected(old('status') === 'draft')>Draft (For Approval)</option>
                                    <option value="published" @selected(old('status') === 'published')>Published</option>
                                    <option value="hidden" @selected(old('status') === 'hidden')>Hidden</option>
                                </select>
                                @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        {{-- CONTENT BODY --}}
                        <div class="mb-4">
                            <label for="body" class="form-label fw-bold">Content</label>
                            <textarea class="form-control @error('body') is-invalid @enderror"
                                      id="body" name="body" rows="12" 
                                      placeholder="Write your story here..." required>{{ old('body') }}</textarea>
                            @error('body') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- TAGS (MULTIPLE SELECT) --}}
                        <div class="mb-4">
                            <label for="tags" class="form-label fw-bold">Tags</label>
                            <select class="form-select @error('tags') is-invalid @enderror"
                                    id="tags" name="tags[]" multiple style="height: 120px;">
                                @foreach($tags as $tag)
                                    <option value="{{ $tag->id }}" 
                                        @selected(is_array(old('tags')) && in_array($tag->id, old('tags')))>
                                        {{ $tag->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('tags') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- FORM BUTTONS --}}
                        <div class="d-flex justify-content-end gap-2 border-top pt-4">
                            <a href="{{ route('contents.index') }}" class="btn btn-light border">
                                <i class="bi bi-x-circle"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-check-circle"></i> Save Post
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Add some custom styling for the focus state --}}
<style>
    .form-control:focus, .form-select:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.1);
    }
    .card { border: none; border-radius: 12px; }
    .card-header { border-radius: 12px 12px 0 0 !important; }
</style>
@endsection