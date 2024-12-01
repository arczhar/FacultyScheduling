@extends('layouts.admin')
<style>
    #start_time, #end_time {
        width: 100%;
        height: 38px;
        font-size: 16px;
        padding: 6px 12px;
        border-radius: 4px;
        border: 1px solid #ced4da;
        background-color: #fff;
    }
</style>

@section('title', 'Edit Schedule')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Edit Schedule</h1>

    <!-- Validation Modal -->
    <div class="modal fade" id="validationModal" tabindex="-1" role="dialog" aria-labelledby="validationModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="validationModalLabel">Message</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Dynamic message will be displayed here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteConfirmationModal" tabindex="-1" role="dialog" aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteConfirmationModalLabel">Confirm Delete</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this schedule?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteButton">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('admin.schedules.update', $schedule->id) }}" method="POST" id="editScheduleForm">
        @csrf
        @method('PUT')

        <!-- Faculty Selection -->
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="faculty_id">Faculty</label>
                <select class="form-control" id="faculty_id" name="faculty_id" disabled>
                    @foreach($faculties as $faculty)
                        <option value="{{ $faculty->id }}" {{ $schedule->faculty_id == $faculty->id ? 'selected' : '' }}>
                            {{ $faculty->first_name }} {{ $faculty->last_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-6">
                <label for="position">Position</label>
                <input type="text" class="form-control" id="position" name="position" value="{{ $schedule->faculty->position }}" readonly>
            </div>
        </div>

        <!-- Faculty Schedule Table -->
        <h6 class="mt-4">Faculty's Schedules</h6>
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
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="schedule_table_body">
                @foreach($facultySchedules as $facultySchedule)
                <tr id="schedule-row-{{ $facultySchedule->id }}">
                    <td>{{ $facultySchedule->subject->subject_code }}</td>
                    <td>{{ $facultySchedule->subject->subject_description }}</td>
                    <td>{{ $facultySchedule->subject->type }}</td>
                    <td>{{ $facultySchedule->subject->credit_units }}</td>
                    <td>{{ $facultySchedule->day }}</td>
                    <td>{{ $facultySchedule->room->room_name }}</td>
                    <td>{{ $facultySchedule->start_time }} - {{ $facultySchedule->end_time }}</td>
                    <td>
                        <button class="btn btn-warning btn-sm edit-schedule" data-id="{{ $facultySchedule->id }}">Edit</button>
                        <button class="btn btn-danger btn-sm delete-schedule" data-id="{{ $facultySchedule->id }}">Delete</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Schedule Details -->
        <div class="form-row">
            <div class="form-group col-md-3">
                <label for="day">Day</label>
                <select class="form-control" id="day" name="day" required>
                    <option value="Monday" {{ $schedule->day == 'Monday' ? 'selected' : '' }}>Monday</option>
                    <option value="Tuesday" {{ $schedule->day == 'Tuesday' ? 'selected' : '' }}>Tuesday</option>
                    <option value="Wednesday" {{ $schedule->day == 'Wednesday' ? 'selected' : '' }}>Wednesday</option>
                    <option value="Thursday" {{ $schedule->day == 'Thursday' ? 'selected' : '' }}>Thursday</option>
                    <option value="Friday" {{ $schedule->day == 'Friday' ? 'selected' : '' }}>Friday</option>
                </select>
            </div>
            <div class="form-group col-md-3">
                <label for="start_time">Start Time</label>
                <select id="start_time" name="start_time" class="form-control" required>
                    <option value="">Select Start Time</option>
                    @foreach($timeOptions as $time)
                        <option value="{{ $time }}" {{ $schedule->start_time == $time ? 'selected' : '' }}>{{ $time }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-3">
                <label for="end_time">End Time</label>
                <select id="end_time" name="end_time" class="form-control" required>
                    <!-- Dynamically populate end times -->
                </select>
            </div>
            <div class="form-group col-md-3">
                <label for="room_id">Room</label>
                <select class="form-control" id="room_id" name="room_id" required>
                    <option value="">Select Room</option>
                    @foreach($rooms as $room)
                        <option value="{{ $room->id }}" {{ $schedule->room_id == $room->id ? 'selected' : '' }}>
                            {{ $room->room_name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Buttons -->
        <button type="submit" class="btn btn-primary">Save Changes</button>
        <a href="{{ route('admin.schedules.index') }}" class="btn btn-secondary">Back to List</a>
    </form>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function () {
    $('#start_time').change(function () {
        const startTime = $(this).val();
        const endTimeField = $('#end_time');

        endTimeField.find('option').remove();
        if (startTime) {
            const [startHours, startMinutes] = startTime.split(':').map(Number);
            const startTotalMinutes = startHours * 60 + startMinutes;

            for (let hours = 0; hours < 24; hours++) {
                for (let minutes = 0; minutes < 60; minutes += 30) {
                    const totalMinutes = hours * 60 + minutes;
                    if (totalMinutes > startTotalMinutes) {
                        const formattedTime = String(hours).padStart(2, '0') + ':' + String(minutes).padStart(2, '0');
                        endTimeField.append(`<option value="${formattedTime}">${formattedTime}</option>`);
                    }
                }
            }

            endTimeField.prop('disabled', false);
        } else {
            endTimeField.prop('disabled', true);
        }
    });

    $('#end_time').prop('disabled', true);
});
</script>
@endpush
