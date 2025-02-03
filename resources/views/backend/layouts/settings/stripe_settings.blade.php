@extends('backend.app')

@section('title', 'Stripe Settings')

@section('content')
    {{-- PAGE-HEADER --}}
    <div class="page-header">
        <div>
            <h1 class="page-title">Stripe Settings</h1>
        </div>
        <div class="ms-auto pageheader-btn">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0);">Settings</a></li>
                <li class="breadcrumb-item active" aria-current="page">Stripe Settings</li>
            </ol>
        </div>
    </div>
    {{-- PAGE-HEADER --}}


    <div class="row">
        <div class="col-lg-12 col-xl-12 col-md-12 col-sm-12">
            <div class="card box-shadow-0">
                <div class="card-body">
                    <form method="post" action="{{ route('stripe.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')
                        <div class="row mb-4">
                            <label for="mail_host" class="col-md-3 form-label">STRIPE KEY</label>
                            <div class="col-md-9">
                                <input class="form-control @error('STRIPE_PK') is-invalid @enderror" id="STRIPE_PK"
                                    name="STRIPE_PK" placeholder="Enter your Stripe Key" type="text"
                                    value="{{ env('STRIPE_PK') }}">
                                @error('STRIPE_PK')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-4">
                            <label for="mail_password" class="col-md-3 form-label">STRIPE SECRET</label>
                            <div class="col-md-9">
                                <input class="form-control @error('STRIPE_SK') is-invalid @enderror" id="STRIPE_SK"
                                    name="STRIPE_SK" placeholder="Enter your Stripe Secrect" type="text"
                                    value="{{ env('STRIPE_SK') }}">
                                @error('STRIPE_SK')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row justify-content-end">
                            <div class="col-sm-9">
                                <div>
                                    <button class="btn btn-primary" type="submit">Submit</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
