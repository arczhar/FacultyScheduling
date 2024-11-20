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

    <!-- Add Schedule Button -->
    <button type="button" class="btn btn-primary mb-4" data-toggle="modal" data-target="#addScheduleModal">
        Add Schedule
    </button>

    <!-- Schedules Table -->
    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-hover table-striped">
                <thead class="thead-dark">
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
                    <td colspan="7" class="text-center">No schedules available.</td>
                </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Schedule Modal -->
<div class="modal fade" id="addScheduleModal" tabindex="-1" role="dialog" aria-labelledby="addScheduleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="addScheduleModalLabel">Add Schedule</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="{{ route('admin.schedules.store') }}">
                @csrf
                <div class="modal-body">
                    <!-- Faculty -->
                    <div class="form-group">
                        <label for="faculty_id">Faculty</label>
                        <select name="faculty_id" id="faculty_id" class="form-control" required>
                            @foreach($faculties as $faculty)
                                <option value="{{ $faculty->id }}">{{ $faculty->first_name }} {{ $faculty->last_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <!-- Subject -->
                    <div class="form-group">
                        <label for="subject_id">Subject</label>
                        <select name="subject_id" id="subject_id" class="form-control" required>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}">{{ $subject->subject_code }} - {{ $subject->subject_description }}</option>
                            @endforeach
                        </select>
                    </div>
                    <!-- Room -->
                    <div class="form-group">
                        <label for="room_id">Room</label>
                        <select name="room_id" id="room_id" class="form-control" required>
                            @foreach($rooms as $room)
                                <option value="{{ $room->id }}">{{ $room->room_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <!-- Day -->
                    <div class="form-group">
                        <label for="day">Day</label>
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
                    <div class="form-group">
                        <label for="start_time">Start Time</label>
                        <input type="time" name="start_time" id="start_time" class="form-control" required>
                    </div>
                    <!-- End Time -->
                    <div class="form-group">
                        <label for="end_time">End Time</label>
                        <input type="time" name="end_time" id="end_time" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
