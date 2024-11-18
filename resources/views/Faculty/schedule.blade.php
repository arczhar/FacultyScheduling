@extends('layouts.app')

@section('title', 'Faculty Schedule')

@section('content')
<div class="container mt-4">
    <!-- Faculty Details -->
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <h5 class="card-title">Faculty Details</h5>
            <p><strong>Name:</strong> {{ $faculty->first_name }} {{ $faculty->middle_initial }} {{ $faculty->last_name }}</p>
            <p><strong>ID Number:</strong> {{ $faculty->id_number }}</p>
            <p><strong>Position:</strong> {{ $faculty->position }}</p>
        </div>
    </div>

    <!-- Schedule Table -->
    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="card-title">My Schedule</h5>
            <table class="table table-bordered table-striped mt-3">
                <thead>
                    <tr>
                        <th>Subject Code</th>
                        <th>Subject Description</th>
                        <th>Day</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Room</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($schedules as $schedule)
                        <tr>
                            <td>{{ $schedule->subject->subject_code }}</td>
                            <td>{{ $schedule->subject->subject_description }}</td>
                            <td>{{ $schedule->day }}</td>
                            <td>{{ \Carbon\Carbon::parse($schedule->start_time)->format('h:i A') }}</td>
                            <td>{{ \Carbon\Carbon::parse($schedule->end_time)->format('h:i A') }}</td>
                            <td>{{ $schedule->room->room_name }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No schedules available.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
