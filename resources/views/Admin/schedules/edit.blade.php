@extends('layouts.admin')

@section('title', 'Edit Schedule')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Edit Schedule</h1>
    <form action="{{ route('admin.schedules.update', $schedule->id) }}" method="POST" id="editScheduleForm">
        @csrf
        @method('PUT')

        <!-- Faculty Selection -->
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="faculty_id">Faculty</label>
                <select class="form-control" id="faculty_id" name="faculty_id" required>
                    <option value="">Select Faculty</option>
                    @foreach($faculties as $faculty)
                        <option value="{{ $faculty->id }}" {{ $schedule->faculty_id == $faculty->id ? 'selected' : '' }}>
                            {{ $faculty->first_name }} {{ $faculty->last_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-6">
                <label for="position">Position</label>
                <input type="text" class="form-control" id="position" name="position" value="{{ $schedule->faculty->position ?? '' }}" readonly>
            </div>
        </div>

        <!-- Semester and School Year -->
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="semester">Semester</label>
                <input type="text" class="form-control" id="semester" name="semester" value="2nd Sem" readonly>
            </div>
            <div class="form-group col-md-6">
                <label for="school_year">School Year</label>
                <input type="text" class="form-control" id="school_year" name="school_year" value="2024-2025" readonly>
            </div>
        </div>

        <!-- Subject Selection -->
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="subject_code">Subject Code</label>
                <select class="form-control" id="subject_code" name="subject_id" required>
                    <option value="">Select Subject</option>
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}" {{ $schedule->subject_id == $subject->id ? 'selected' : '' }}>
                            {{ $subject->subject_code }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-6">
                <label for="subject_description">Subject Description</label>
                <input type="text" class="form-control" id="subject_description" name="subject_description" value="{{ $schedule->subject->subject_description ?? '' }}" readonly>
            </div>
        </div>

        <!-- Additional Subject Details -->
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="subject_type">Subject Type</label>
                <input type="text" class="form-control" id="subject_type" name="subject_type" value="{{ $schedule->subject->type ?? '' }}" readonly>
            </div>
            <div class="form-group col-md-6">
                <label for="subject_units">Subject Units</label>
                <input type="text" class="form-control" id="subject_units" name="subject_units" value="{{ $schedule->subject->credit_units ?? '' }}" readonly>
            </div>
        </div>

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
                <input type="time" class="form-control" id="start_time" name="start_time" value="{{ $schedule->start_time }}" required>
            </div>
            <div class="form-group col-md-3">
                <label for="end_time">End Time</label>
                <input type="time" class="form-control" id="end_time" name="end_time" value="{{ $schedule->end_time }}" required>
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
    $('#subject_code').change(function () {
        var subjectId = $(this).val();
        if (subjectId) {
            $.ajax({
                url: '/get-subject-details/' + subjectId,
                method: 'GET',
                success: function (response) {
                    $('#subject_description').val(response.subject_description);
                    $('#subject_type').val(response.type);
                    $('#subject_units').val(response.units);
                },
                error: function (xhr) {
                    console.error('Error fetching subject details:', xhr.responseText);
                }
            });
        } else {
            $('#subject_description').val('');
            $('#subject_type').val('');
            $('#subject_units').val('');
        }
    });
});
</script>
@endpush
