@extends('layouts.backend')
@section('title', 'E-Perpus - About Form')
@section('content')
<div class="col-md-12">
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb
        bg-light p-3 rounded shadow-sm mb-3">
        <li class="breadcrumb-item">
            <a href="{{ route('about.index') }}" class="text-decoration-none text-primary fw-semibold">
            About Us
            </a>
        </li>
        <li class="breadcrumb-item active fw-bold text-dark" aria-current="page">
            About Form
        </li>
    </ol>
  </nav>
    <div class="card shadow-lg">
        <div class="card-header">
        <h5 class="mb-0">About Form</h5>
        </div>
        <div class="card-body">
        <form action="{{ isset($about) ? route('about.update', $about->id) : route('about.store') }}" method="POST">
            @csrf
            @if(isset($about))
            @method('PUT')
            @endif
    
            <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" class="form-control" id="title" name="title" value="{{ old('title', isset($about) ? $about->title : '') }}" required>
            </div>
    
            <div class="mb-3">
            <label for="content" class="form-label">Content</label>
            <textarea class="form-control" id="content" name="content" rows="5" required>{{ old('content', isset($about) ? $about->content : '') }}</textarea>
            </div>
    
            <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" {{ old('is_active', isset($about) && $about->is_active ? 'checked' : '') }}>
            <label class="form-check-label" for="is_active">
                Active
            </label>
            </div>

            <button type="submit" class="btn btn-primary">
            {{ isset($about) ? 'Update' : 'Create' }}
            </button>
        </form>
        </div>
    </div>
</div>
@endsection