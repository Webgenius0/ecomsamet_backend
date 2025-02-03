@extends('backend.app')

@section('title', 'Booking')

@section('content')
<div class="page-header">
    <h1 class="page-title">Booking</h1>
</div>

@if (session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card">
    <div class="card-header">
        <h2>Booking Details</h2>
    </div>

    <div class="card-body">
        @if($bookings->isNotEmpty())

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>User Name </th>
                    <th>Services Name </th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Payment Satus</th>
                    <th>Service Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>

                @foreach($bookings as $booking)

                <tr>
                    <td>{{ $booking->apiuser->fullname ?? 'N/A' }}</td> <!-- Corrected -->
                    <td>{{ $booking->service->name ?? 'N/A' }}</td> <!-- Corrected -->
                    <td>{{ $booking->date ?? 'N/A' }}</td>
                    <td>{{ $booking->time ?? 'N/A' }}</td>
                    <td>{{ $booking->status ?? 'N/A' }}</td>
                    <td>
                        @if(!empty($booking->service->image) && is_array($booking->service->image))
                            <img src="{{ asset('storage/' . $booking->service->image[0]) }}" alt="Service Image" style="width: 100px; height: auto;">
                        @elseif(!empty($booking->service->image))
                            <img src="{{ asset('storage/' . json_decode($booking->service->image, true)[0]) }}" alt="Service Image" style="width: 100px; height: auto;">
                        @else
                            No Image
                        @endif
                    </td>
                    <td>
                        <!-- Edit Button -->
                        {{-- <a href="{{ route('education.edit', $booking->id) }}" class="btn btn-warning btn-sm">
                            Edit
                        </a> --}}

                        <!-- Delete Form -->
                        <form action="" method="POST" style="display:inline-block;">
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
        <p>No education insights available.</p>
        @endif
    </div>
</div>
@endsection
