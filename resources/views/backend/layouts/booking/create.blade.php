@extends('backend.app')

@section('title', 'System Settings')

@section('content')
    {{-- PAGE-HEADER --}}
    <div class="page-header">
        <div>
            <h1 class="page-title">Add Education Insights</h1>
        </div>
    </div>
    {{-- PAGE-HEADER --}}

    <div class="row">
        <div class="col-lg-12 col-xl-12 col-md-12 col-sm-12">
            <div class="card box-shadow-0">
                <div class="card-body">
                    <form method="POST" action="{{ route('education.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="title" class="form-label">Title:</label>
                                    <input type="text" 
                                        class="form-control @error('title') is-invalid @enderror"
                                        name="title" 
                                        placeholder="Enter title" 
                                        id="title"
                                        value="{{ old('title') }}">
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
                                        placeholder="Write a description">{{ old('description') }}</textarea>
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
                                        data-default-file="">
                                    @error('image')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group mt-3">
                            <button class="btn btn-primary" type="submit">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
