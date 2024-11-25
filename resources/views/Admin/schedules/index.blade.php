@extends('layouts.admin')

@section('title', 'List of Schedules')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">List of Schedules</h1>

    <!-- Add Schedule Button -->
    <a href="{{ Auth::user()->role === 'admin' ? route('admin.schedules.create') : route('programchair.schedules.create') }}" class="btn btn-primary mb-4">Add Schedule</a>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Schedule Table -->
    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-hover table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th>Faculty</th>
                        <th>Subject</th>
                        <th>Day</th>
                        <th>Time</th>
                        <th>Room</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($schedules as $schedule)
                <tr id="schedule-row-{{ $schedule->id }}">
                    <td>{{ $schedule->faculty->first_name ?? 'N/A' }} {{ $schedule->faculty->last_name ?? '' }}</td>
                    <td>{{ $schedule->subject->subject_code ?? 'N/A' }} - {{ $schedule->subject->subject_description ?? '' }}</td>
                    <td>{{ $schedule->day }}</td>
                    <td>{{ \Carbon\Carbon::parse($schedule->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('h:i A') }}</td>
                    <td>{{ $schedule->room->room_name ?? 'N/A' }}</td>
                    <td>
                        <a href="{{ Auth::user()->role === 'admin' ? route('admin.schedules.edit', $schedule->id) : route('programchair.schedules.edit', $schedule->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <button class="btn btn-danger btn-sm delete-schedule-btn" data-id="{{ $schedule->id }}">Delete</button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">No schedules available.</td>
                </tr>
                @endforelse
                </tbody>
            </table>
            <div class="mt-3">
                {{ $schedules->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        $(document).on('click', '.delete-schedule-btn', function () {
            var scheduleId = $(this).data('id');
            if (confirm('Are you sure you want to delete this schedule?')) {
                $.ajax({
                    url: `{{ route(Auth::user()->role === 'Admin' ? 'admin.schedules.destroy' : 'programchair.schedules.destroy', '') }}/${scheduleId}`,
                    method: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        if (response.success) {
                            alert(response.message);
                            $(`#schedule-row-${scheduleId}`).remove();
                        }
                    },
                    error: function (xhr) {
                        console.error('Error deleting schedule:', xhr.responseText);
                    }
                });
            }
        });
    });
</script>
@endpush
