@extends('layouts.admin')

@section('title', 'View Faculty Schedule')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">View Faculty Schedule</h1>

    <!-- Faculty Details -->
    <div class="form-row">
        <div class="form-group col-md-6">
            <label for="faculty_name">Faculty Name</label>
            <input type="text" class="form-control" id="faculty_name" name="faculty_name" value="{{ $faculty->first_name }} {{ $faculty->last_name }}" readonly>
        </div>
        <div class="form-group col-md-6">
            <label for="position">Position</label>
            <input type="text" class="form-control" id="position" name="position" value="{{ $faculty->position }}" readonly>
        </div>
    </div>

    <!-- Redirect Button -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="mt-4">Faculty's Schedules</h6>
        <a href="{{ auth()->user()->role === 'admin' ? route('admin.schedules.create') : route('programchair.schedules.create') }}" 
   class="btn btn-primary">
    Go to Add/Edit Schedule
</a>




</a>


    </div>

    <!-- Faculty Schedule Table -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Subject Code</th>
                <th>Description</th>
                <th>Type</th>
                <th>Units</th>
                <th>Day</th>
                <th>Room</th>
                <th>Time</th>
            </tr>
        </thead>
        <tbody>
            @forelse($schedules as $schedule)
                <tr>
                    <td>{{ $schedule->subject->subject_code ?? 'N/A' }}</td>
                    <td>{{ $schedule->subject->subject_description ?? 'N/A' }}</td>
                    <td>{{ $schedule->subject->type ?? 'N/A' }}</td>
                    <td>{{ $schedule->subject->credit_units ?? 'N/A' }}</td>
                    <td>{{ $schedule->day }}</td>
                    <td>{{ $schedule->room->room_name ?? 'N/A' }}</td>
                    <td>{{ $schedule->start_time }} - {{ $schedule->end_time }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">No schedules available for this faculty.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Back to List Button -->
    <a href="{{ route(auth()->user()->role === 'admin' ? 'admin.schedules.index' : 'programchair.schedules.index') }}" class="btn btn-secondary mt-3">Back to List</a>
</div>
@endsection
