@extends('backend.app')

@section('title', 'Category')

@section('content')
<div class="page-header">
    <h1 class="page-title">Category</h1>
</div>

@if (session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card">
    <div class="card-header">
        <h2>Category Details</h2>
    </div>
    <div class="mb-3" style="display: flex; justify-content: end; margin: 0 20px">
        <a href="{{ route('category.create') }}" class="btn btn-primary">Add New</a>
    </div>
    <div class="card-body">
        @if($categorys->isNotEmpty())
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categorys as $category)
                <tr>
                    <td>{{ $category->name }}</td>
                    <td>{!! $category->description !!}</td>
                    <td>
                        <!-- Edit Button -->
                        <a href="{{ route('category.edit', $category->id) }}" class="btn btn-warning btn-sm">
                            Edit
                        </a>

                        <!-- Delete Form -->
                        <form action="{{ route('category.destory', $category->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this insight?')">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <p>No Category available.</p>
        @endif
    </div>
</div>
@endsection
