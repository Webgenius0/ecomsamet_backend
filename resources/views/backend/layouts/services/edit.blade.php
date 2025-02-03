@extends('backend.app')

@section('title', 'Edit Service')

@section('content')
<div class="page-header">
    <h1 class="page-title">Edit Service</h1>
</div>

@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="row">
    <div class="col-lg-12 col-md-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('service.update', $service->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')  <!-- Method spoofing for PUT request -->

                    <div class="row">
                        <div class="col-lg-6 col-md-6">
                            <div class="form-group">
                                <label class="form-label">Category</label>
                                <select name="category_id" class="form-control @error('category_id') is-invalid @enderror" required>
                                    <option value="" disabled>Select a Category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id', $service->category_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-lg-6 col-md-6">
                            <div class="form-group">
                                <label class="form-label">Service Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" placeholder="Enter Service Name" value="{{ old('name', $service->name) }}" required>
                                @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6 col-md-6">
                            <div class="form-group">
                                <label class="form-label">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" name="description" placeholder="Enter Description" rows="4" required>{{ old('description', $service->description) }}</textarea>
                                @error('description')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-lg-6 col-md-6">
                            <div class="form-group">
                                <label class="form-label">Price</label>
                                <input type="number" class="form-control @error('price') is-invalid @enderror" name="price" placeholder="Enter Price" step="0.01" value="{{ old('price', $service->price) }}" required>
                                @error('price')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6 col-md-6">
                            <div class="form-group">
                                <label class="form-label">Images</label>
                                <input type="file" class="form-control @error('image') is-invalid @enderror" name="image[]" multiple>
                                @error('image.*')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        @if ($service->image && is_array($service->image)) <!-- Check if the images exist and are in array format -->
    <div class="col-lg-12 col-md-12">
        <div class="form-group">
            <label class="form-label">Current Images</label>
            <div class="row">
                @foreach ($service->image as $index => $image)
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="card">
                            <img src="{{ asset('storage/' . $image) }}" alt="Current Image" class="img-thumbnail" width="150">
                            <div class="form-check mt-2">
                                <input type="checkbox" class="form-check-input" name="delete_images[]" value="{{ $index }}" id="delete_image_{{ $index }}">
                                <label class="form-check-label" for="delete_image_{{ $index }}">Delete</label>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endif
                    </div>

                    <div class="row">

                        <div class="col-lg-6 col-md-6">
                            <div class="form-group">
                                <label class="form-label">Duration (in minutes)</label>
                                <input type="text" class="form-control @error('duration') is-invalid @enderror" name="duration" placeholder="Enter Duration" value="{{ old('duration', $service->duration) }}" required>
                                @error('duration')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12 col-md-12">
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Update Service</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
