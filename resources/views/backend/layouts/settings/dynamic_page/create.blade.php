@extends('backend.app')

@section('title', 'Dynamic Page Create')

@section('content')
    {{-- PAGE-HEADER --}}
    <div class="page-header">
        <div>
            <h1 class="page-title">Dynamic Page Form</h1>
        </div>
        <div class="ms-auto pageheader-btn">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0);">Settings</a></li>
                <li class="breadcrumb-item active" aria-current="page">Dynamic Page</li>
            </ol>
        </div>
    </div>
    {{-- PAGE-HEADER END --}}


    <div class="row">
        <div class="col-lg-12 col-xl-12 col-md-12 col-sm-12">
            <div class="card box-shadow-0">
                <div class="card-body">
                    <form method="post" action="{{ route('dynamic_page.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="page_title" class="form-label">Title:</label>
                            <input type="text" class="form-control @error('page_title') is-invalid @enderror"
                                name="page_title" placeholder="title" id="title" value="{{ old('page_title') }}">
                            @error('page_title')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="summernote" class="form-label">Content:</label>
                            <textarea class="form-control @error('page_content') is-invalid @enderror" id="summernote" name="page_content">{{ old('page_content') }}</textarea>
                            @error('page_content')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <button class="btn btn-primary" type="submit">Submit</button>
                            <a href="{{ route('dynamic_page.index') }}" class="btn btn-danger me-2">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
