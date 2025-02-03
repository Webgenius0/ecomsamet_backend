@extends('backend.app')

@section('title', 'Favorites')

@section('content')
<div class="page-header">
    <h1 class="page-title">Favorites</h1>
</div>

@if (session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card">
    <div class="card-header">
        <h2>Favorites Details</h2>
    </div>

    <div class="card-body">
        @if($favorites->isNotEmpty())
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Services Name</th>
                    <th>User Name</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($favorites as $favorite)
                <tr>
                    <td>{{ $favorite->service->name }}</td>
                    <td>{{ $favorite->apiuser->fullname }}</td>

                    <td>
                        @if(is_array($favorite->service->image))
                            <img src="{{ asset('storage/' . $favorite->service->image[0]) }}" alt="Service Image" width="100">
                        @else
                            <img src="{{ asset('storage/' . $favorite->service->image) }}" alt="Service Image" width="100">
                        @endif
                    </td>

                    <td>
                        <!-- Delete Button with SweetAlert -->
                        <button type="button" class="btn btn-danger btn-sm delete-btn" data-id="{{ $favorite->id }}">
                            Delete
                        </button>

                        <!-- Hidden delete form -->
                        <form id="delete-form-{{ $favorite->id }}" action="{{ route('favorite.destroy', $favorite->id) }}" method="POST" style="display: none;">
                            @csrf
                            @method('DELETE')
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

<!-- Include SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function () {
                let favoriteId = this.getAttribute('data-id');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('delete-form-' + favoriteId).submit();
                    }
                });
            });
        });
    });
</script>
@endsection
