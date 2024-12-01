@extends('layouts.admin')

@section('title', 'Faculty Schedules')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Schedules for {{ $faculty->first_name }} {{ $faculty->last_name }}</h1>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Subject Code</th>
                <th>Description</th>
                <th>Day</th>
                <th>Time</th>
                <th>Room</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($schedules as $schedule)
                <tr>
                    <td>{{ $schedule->subject->subject_code ?? 'N/A' }}</td>
                    <td>{{ $schedule->subject->subject_description ?? 'N/A' }}</td>
                    <td>{{ $schedule->day }}</td>
                    <td>{{ $schedule->start_time }} - {{ $schedule->end_time }}</td>
                    <td>{{ $schedule->room->room_name ?? 'N/A' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">No schedules available for this faculty.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
