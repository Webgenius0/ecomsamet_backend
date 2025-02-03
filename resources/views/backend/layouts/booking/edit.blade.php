@extends('backend.app')

@section('title', 'Edit Education Insights')

@section('content')
    {{-- PAGE-HEADER --}}
    <div class="page-header">
        <div>
            <h1 class="page-title">Edit Education Insight</h1>
        </div>
    </div>
    {{-- PAGE-HEADER --}}

    <div class="row">
        <div class="col-lg-12 col-xl-12 col-md-12 col-sm-12">
            <div class="card box-shadow-0">
                <div class="card-body">
                    {{-- Form for updating the education insight --}}
                    <form method="POST" action="{{ route('education.update', $event->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT') {{-- For PUT or PATCH method --}}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="title" class="form-label">Title:</label>
                                    <input type="text" 
                                        class="form-control @error('title') is-invalid @enderror"
                                        name="title" 
                                        placeholder="Enter title" 
                                        id="title"
                                        value="{{ old('title', $event->title) }}">
                                    @error('title')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="summernote" class="form-label">Description:</label>
                                    <textarea 
                                        class="form-control @error('description') is-invalid @enderror" 
                                        id="summernote" 
                                        name="description"
                                        placeholder="Write a description">{{ old('description', $event->description) }}</textarea>
                                    @error('description')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="image" class="form-label">Image:</label>
                                    <input 
                                        type="file" 
                                        class="form-control dropify @error('image') is-invalid @enderror" 
                                        name="image"
                                        id="image"
                                        data-default-file="{{ asset($event->image) }}">
                                    @error('image')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                @if($event->image)
                                    <img src="{{ asset($event->image) }}" alt="Current Image" style="width: 150px; height: auto; margin-top: 10px;">
                                @endif
                            </div>
                        </div>

                        <div class="form-group mt-3">
                            <button class="btn btn-primary" type="submit">Update</button>
                            <a href="{{ route('education.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
