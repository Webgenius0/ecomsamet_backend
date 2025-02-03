@extends('backend.app')

@section('title', 'User list')

@section('content')
<div class="page-header">
    <h1 class="page-title">User List</h1>
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
                            <tr>
                                <th>User Name</th>
                                <th>Email Address</th>
                                <th>Phone Number</th>
                                <th>Profile Image</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                            <tr>
                                <td>{{ $user->fullname ?? 'N/A' }}</td>
                                <td>{{ $user->email ?? 'N/A' }}</td>
                                <td>{{ $user->phone ?? 'N/A' }}</td>
                                <td>
                                    @if(is_array($user->image))
                                        <img src="{{ asset('storage/' . $user->image[0]) }}" alt="User Image" width="100">
                                    @else
                                        <img src="{{ asset('storage/' . $user->image) }}" alt="User Image" width="100">
                                    @endif
                                </td>
                                <td>
                                    <!-- Edit Button -->
                                    {{-- <a href="{{ route('user.edit', $user->id) }}" class="btn btn-warning btn-sm">Edit</a> --}}

                                    <!-- Delete Button -->
                                    <button class="btn btn-danger btn-sm delete-btn" data-id="{{ $user->id }}">Delete</button>

                                    <!-- Hidden Delete Form -->
                                    <form id="delete-form-{{ $user->id }}" action="{{ route('user.destroy', $user->id) }}" method="POST" style="display: none;">
                                        @csrf
                                        @method('DELETE')
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

<!-- Include SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function () {
                let userId = this.getAttribute('data-id');
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
                        document.getElementById('delete-form-' + userId).submit();
                    }
                });
            });
        });
    });
</script>
@endsection
