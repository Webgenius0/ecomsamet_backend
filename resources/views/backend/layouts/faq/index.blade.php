@extends('backend.app')

@section('title', 'FAQ')

@section('content')
<div class="page-header">
    <h1 class="page-title">FAQ Settings</h1>
</div>

@if (session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card">
    <div class="card-header">
        <h2>FAQ Details</h2>
    </div>
    <div class="mb-3" style="display: flex; justify-content: end; margin: 0 20px">
        <a href="{{ route('faq.create') }}" class="btn btn-primary">Add New</a>
    </div>
    <div class="card-body">
        @if($faqs->count() > 0) <!-- Using count() to check if there are any FAQs -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Total U</th>
                    <th>Total U Work</th>
                    <th>Use Total U</th>
                    <th>Total U Free Use</th>
                    <th>Total U Data Secure</th>
                    <th>Total U Data</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($faqs as $faq)
                <tr>
                    <td>{{ $faq->totalU }}</td>
                    <td>{{ $faq->totalU_work }}</td>
                    <td>{{ $faq->use_totalU }}</td>
                    <td>{{ $faq->totalU_free_use }}</td>
                    <td>{{ $faq->totalU_data_secure }}</td>
                    <td>{{ $faq->totalU_data }}</td>
                    <td>
                        <!-- Edit Button -->
                        <a href="{{ route('faq.edit', $faq->id) }}" class="btn btn-warning btn-sm">
                            Edit
                        </a>

                        <!-- Delete Form -->
                        <form action="{{ route('faq.destroy', $faq->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this FAQ?')">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <p>No FAQ available.</p>
        @endif
    </div>
</div>
@endsection
