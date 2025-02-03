@extends('backend.app')

@section('title', 'service List')

@section('content')
<div class="page-header">
    <h1 class="page-title">Services</h1>
</div>

@if (session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-vcenter text-nowrap mb-0">
                        <thead>
                            <div class="mb-3" style="display: flex; justify-content: end; margin: 0 20px">
                                <a href="{{ route('service.create') }}" class="btn btn-primary">Add New</a>
                            </div>
                            <tr>
                                <th class="w-1">ID</th>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Price</th>
                                <th>Duration</th>
                                <th>Image</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($services as $service)
                            <tr>
                                <td>{{ $service->id }}</td>
                                <td>{{ $service->name }}</td>
                                <td>{{ $service->description }}</td>
                                <td>{{ $service->price }}</td>
                                <td>{{ $service->duration }}</td>
                                <td>
                                    @if(!empty($service->image))
                                        @foreach($service->image as $imgUrl)
                                            <img src="{{ $imgUrl }}" alt="Service Image" width="100" style="margin: 5px;">
                                        @endforeach
                                    @else
                                        <img src="{{ asset('storage/placeholder.jpg') }}" alt="Placeholder Image" width="100">
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('service.edit', $service->id) }}" class="btn btn-warning btn-sm">
                                        Edit
                                    </a>
                                    <form action="{{ route('service.destroy', $service->id) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this service?')">
                                            Delete
                                        </button>
                                    </form>
                                </td>                               
                            </tr>
                            @endforeach                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection