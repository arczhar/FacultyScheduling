<!-- resources/views/admin/schedules/index.blade.php -->

@extends('layouts.admin')

@section('title', 'Manage Schedules')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Manage Schedules</h1>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Button to trigger the Add Schedule modal -->
    <button class="btn btn-primary mb-4" data-bs-toggle="modal" data-bs-target="#addScheduleModal">
        Add Schedule
    </button>

    <!-- Schedules Table -->
    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-hover">
                <thead>
                    <tr>
                        
                        <th>Faculty</th>
                        <th>Subject</th>
                        <th>Day</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Room</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($schedules as $schedule)
                <tr>
                    
                    <td>{{ $schedule->faculty->first_name ?? 'N/A' }} {{ $schedule->faculty->last_name ?? '' }}</td>
                    <td>{{ $schedule->subject->subject_code ?? 'N/A' }} - {{ $schedule->subject->subject_description ?? '' }}</td>
                    <td>{{ $schedule->day }}</td>
                    <td>{{ \Carbon\Carbon::parse($schedule->start_time)->format('h:i A') }}</td>
                    <td>{{ \Carbon\Carbon::parse($schedule->end_time)->format('h:i A') }}</td>
                    <td>{{ $schedule->room->room_name ?? 'N/A' }}</td>
                    <td>
                        <form action="{{ route('admin.schedules.destroy', $schedule->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this schedule?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center">No schedules available.</td>
                </tr>
                @endforelse

                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Schedule Modal -->
<div class="modal fade" id="addScheduleModal" tabindex="-1" aria-labelledby="addScheduleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addScheduleModalLabel">Add Schedule</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('admin.schedules.store') }}">
                @csrf
                <div class="modal-body">
                    <!-- Faculty -->
                    <div class="mb-3">
                        <label for="faculty_id" class="form-label">Faculty</label>
                        <select name="faculty_id" id="faculty_id" class="form-control" required>
                            @foreach($faculties as $faculty)
                                <option value="{{ $faculty->id }}">{{ $faculty->first_name }} {{ $faculty->last_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <!-- Subject -->
                    <div class="mb-3">
                        <label for="subject_id" class="form-label">Subject</label>
                        <select name="subject_id" id="subject_id" class="form-control" required>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}">{{ $subject->subject_code }} - {{ $subject->subject_description }}</option>
                            @endforeach
                        </select>
                    </div>
                    <!-- Room -->
                    <div class="mb-3">
                        <label for="room_id" class="form-label">Room</label>
                        <select name="room_id" id="room_id" class="form-control" required>
                            @foreach($rooms as $room)
                                <option value="{{ $room->id }}">{{ $room->room_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <!-- Day -->
                    <div class="mb-3">
                        <label for="day" class="form-label">Day</label>
                        <select name="day" id="day" class="form-control" required>
                            <option value="Monday">Monday</option>
                            <option value="Tuesday">Tuesday</option>
                            <option value="Wednesday">Wednesday</option>
                            <option value="Thursday">Thursday</option>
                            <option value="Friday">Friday</option>
                            <option value="Saturday">Saturday</option>
                            <option value="Sunday">Sunday</option>
                        </select>
                    </div>
                    <!-- Start Time -->
                    <div class="mb-3">
                        <label for="start_time" class="form-label">Start Time</label>
                        <input type="time" name="start_time" id="start_time" class="form-control" required>
                    </div>
                    <!-- End Time -->
                    <div class="mb-3">
                        <label for="end_time" class="form-label">End Time</label>
                        <input type="time" name="end_time" id="end_time" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
