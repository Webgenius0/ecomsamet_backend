@extends('backend.app')

@section('title', 'Onboard Edit')

@section('content')
{{-- PAGE-HEADER --}}
<div class="page-header">
    <div>
        <h1 class="page-title">Edit Onboard</h1>
    </div>
</div>
{{-- PAGE-HEADER --}}

<div class="row">
    <div class="col-lg-12 col-xl-12 col-md-12 col-sm-12">
        <div class="card box-shadow-0">
            <div class="card-body">
                <form method="POST" action="{{ route('onboard.update', $onboard->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT') <!-- Use PUT method for updating the resource -->

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="old" class="form-label">How Old Are You:</label>
                                <input type="text" class="form-control @error('old') is-invalid @enderror"
                                    name="old" placeholder="Enter your age" id="old" value="{{ old('old', $onboard->old) }}">
                                @error('old')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="find_us" class="form-label">How Did You Find Us:</label>
                                <input type="text" class="form-control @error('find_us') is-invalid @enderror"
                                    name="find_us" placeholder="e.g., Social Media, Friend" id="find_us" value="{{ old('find_us', $onboard->find_us) }}">
                                @error('find_us')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="main_goal" class="form-label">What Is Your Main Goal:</label>
                                <input type="text" class="form-control @error('main_goal') is-invalid @enderror"
                                    name="main_goal" placeholder="Enter your main goal" id="main_goal" value="{{ old('main_goal', $onboard->main_goal) }}">
                                @error('main_goal')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group mt-3">
                        <button class="btn btn-primary" type="submit">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
