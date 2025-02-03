@extends('backend.app')

@section('title', 'Add New Service')

@section('content')
<div class="page-header">
    <h1 class="page-title">Add New Service</h1>    
</div>

@if (session('success'))    
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="row">
    <div class="col-md-12">
        <div class="card">  
            <div class="card-body">
                <form action="{{ route('service.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf {{-- Protect the form with CSRF --}}
                    
                    <!-- Category Dropdown -->
                    <div class="form-group">
                        <label for="category_id">Category</label>
                        <select name="category_id" id="category_id" class="form-control" required>
                            <option value="" disabled selected>Select a Category</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
            
                    <!-- Service Name -->
                    <div class="form-group">
                        <label for="name">Service Name</label>
                        <input type="text" name="name" id="name" class="form-control"  required>
                        @error('name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
            
                    <!-- Description -->
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea name="description" id="description" class="form-control" rows="4"></textarea>
                        @error('description')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
            
                    <!-- Price -->
                    <div class="form-group">
                        <label for="price">Price</label>
                        <input type="number" name="price" id="price" class="form-control" step="0.01"  required>
                        @error('price')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
            
                    <!-- Images -->
                    <div class="form-group">
                        <label for="image">Images</label>
                        <input type="file" name="image[]" id="image" class="form-control" multiple>
                        @error('image.*')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
            
                    <!-- Duration -->
                    <div class="form-group">
                        <label for="duration">Duration (in minutes)</label>
                        <input type="text" name="duration" id="duration" class="form-control" required>
                        @error('duration')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
            
                    <!-- Submit Button -->
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Create Service</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
