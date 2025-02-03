@extends('backend.app')

@section('title', 'Onboard App')

@section('content')
<div class="page-header">
    <h1 class="page-title">Onboard App</h1>
</div>

@if (session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card">
    <div class="card-header">
        <h2>Onboard Apps Details</h2>
    </div>
    <div class="mb-3" style="display: flex; justify-content: end; margin: 0 20px">
        <a href="{{ route('onboard.create') }}" class="btn btn-primary">Add New</a>
    </div>
    <div class="card-body">
        @if($onboards->isNotEmpty())
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>How Old Are You</th>
                    <th>How Did You Find Us</th>
                    <th>What Is Your Main Goal</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($onboards as $onboard)
                <tr>
                    <td>{{ $onboard->old }}</td>
                    <td>{!! $onboard->find_us !!}</td>
                    <td>{!! $onboard->main_goal !!}</td>
                  
                    <td>
                        <!-- Edit Button -->
                        <a href="{{ route('onboard.edit', $onboard->id) }}" class="btn btn-warning btn-sm">
                            Edit
                        </a>

                        <!-- Delete Form -->
                        <form action="{{ route('onboard.destroy', $onboard->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this item?')">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <p>No education insights available.</p>
        @endif
    </div>
</div>
@endsection
