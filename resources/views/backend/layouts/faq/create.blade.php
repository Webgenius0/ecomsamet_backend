@extends('backend.app')

@section('title', 'Add New FAQ')

@section('content')
<div class="page-header">
    <h1 class="page-title">Add New FAQ</h1>
</div>

@if (session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card">
    <div class="card-header">
        <h2>Add New FAQ</h2>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('faq.store') }}">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="totalU" class="form-label">Total U</label>
                        <input type="text" class="form-control @error('totalU') is-invalid @enderror" name="totalU" placeholder="Enter Total U" value="{{ old('totalU') }}">
                        @error('totalU')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="totalU_work" class="form-label">Total U Work</label>
                        <input type="text" class="form-control @error('totalU_work') is-invalid @enderror" name="totalU_work" placeholder="Enter Total U Work" value="{{ old('totalU_work') }}">
                        @error('totalU_work')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="use_totalU" class="form-label">Use Total U</label>
                        <input type="text" class="form-control @error('use_totalU') is-invalid @enderror" name="use_totalU" placeholder="Enter Use Total U" value="{{ old('use_totalU') }}">
                        @error('use_totalU')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="totalU_free_use" class="form-label">Total U Free Use</label>
                        <input type="text" class="form-control @error('totalU_free_use') is-invalid @enderror" name="totalU_free_use" placeholder="Enter Total U Free Use" value="{{ old('totalU_free_use') }}">
                        @error('totalU_free_use')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="totalU_data_secure" class="form-label">Total U Data Secure</label>
                        <input type="text" class="form-control @error('totalU_data_secure') is-invalid @enderror" name="totalU_data_secure" placeholder="Enter Total U Data Secure" value="{{ old('totalU_data_secure') }}">
                        @error('totalU_data_secure')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="totalU_data" class="form-label">Total U Data</label>
                        <input type="text" class="form-control @error('totalU_data') is-invalid @enderror" name="totalU_data" placeholder="Enter Total U Data" value="{{ old('totalU_data') }}">
                        @error('totalU_data')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="form-group mt-3">
                <button type="submit" class="btn btn-primary">Save FAQ</button>
                <a href="{{ route('faq.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
