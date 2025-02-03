@extends('backend.app')

@section('title', 'Edit FAQ')

@section('content')
<div class="page-header">
    <h1 class="page-title">Edit FAQ</h1>
</div>

@if (session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card">
    <div class="card-header">
        <h2>Edit FAQ</h2>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('faq.update', $faq->id) }}">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="totalU" class="form-label">Total U</label>
                        <input type="text" class="form-control @error('totalU') is-invalid @enderror" name="totalU" value="{{ old('totalU', $faq->totalU) }}">
                        @error('totalU')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="totalU_work" class="form-label">Total U Work</label>
                        <input type="text" class="form-control @error('totalU_work') is-invalid @enderror" name="totalU_work" value="{{ old('totalU_work', $faq->totalU_work) }}">
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
                        <input type="text" class="form-control @error('use_totalU') is-invalid @enderror" name="use_totalU" value="{{ old('use_totalU', $faq->use_totalU) }}">
                        @error('use_totalU')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="totalU_free_use" class="form-label">Total U Free Use</label>
                        <input type="text" class="form-control @error('totalU_free_use') is-invalid @enderror" name="totalU_free_use" value="{{ old('totalU_free_use', $faq->totalU_free_use) }}">
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
                        <input type="text" class="form-control @error('totalU_data_secure') is-invalid @enderror" name="totalU_data_secure" value="{{ old('totalU_data_secure', $faq->totalU_data_secure) }}">
                        @error('totalU_data_secure')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="totalU_data" class="form-label">Total U Data</label>
                        <input type="text" class="form-control @error('totalU_data') is-invalid @enderror" name="totalU_data" value="{{ old('totalU_data', $faq->totalU_data) }}">
                        @error('totalU_data')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="form-group mt-3">
                <button type="submit" class="btn btn-primary">Update FAQ</button>
                <a href="{{ route('faq.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
