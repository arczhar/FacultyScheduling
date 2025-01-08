@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h2>Manage Exam Schedule</h2>
    
    <!-- Schedule Form -->
    <div class="card mt-4">
        <div class="card-header bg-primary text-white">
            Add or Edit Exam Schedule
        </div>
        <div class="card-body">
            <form id="examScheduleForm">
                @csrf
                <input type="hidden" id="schedule_id" name="schedule_id">
                
                <div class="row">
                    <!-- Subject Field -->
                    <div class="form-group col-md-4">
                        <label for="subject_id">Subject</label>
                        <select id="subject_id" name="subject_id" class="form-control" required>
                            <option value="">Select Subject</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}">{{ $subject->subject_code }} - {{ $subject->subject_description }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Section Field -->
                    <div class="form-group col-md-4">
                        <label for="section_id">Section</label>
                        <select id="section_id" name="section_id" class="form-control" required>
                            <option value="">Select Section</option>
                            @foreach($sections as $section)
                                <option value="{{ $section->id }}">{{ $section->section_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Room Field -->
                    <div class="form-group col-md-4">
                        <label for="room_id">Room</label>
                        <select id="room_id" name="room_id" class="form-control" required>
                            <option value="">Select Room</option>
                            @foreach($rooms as $room)
                                <option value="{{ $room->id }}">{{ $room->room_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row">
                    <!-- Time Slot Field -->
                    <div class="form-group col-md-6">
                        <label for="time_slot">Time Slot</label>
                        <select id="time_slot" name="time_slot" class="form-control" required>
                            <option value="">Select Time Slot</option>
                            @foreach($timeSlots as $slot)
                                <option value="{{ $slot }}">{{ $slot }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Exam Date Field -->
                    <div class="form-group col-md-6">
                        <label for="exam_date">Exam Date</label>
                        <input type="date" id="exam_date" name="exam_date" class="form-control" required>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="mt-3">
                    <button type="submit" id="saveButton" class="btn btn-success">Save</button>
                    <button type="reset" class="btn btn-secondary">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Schedule List -->
    <div class="card mt-4">
        <div class="card-header bg-secondary text-white">
            Exam Schedules
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>Subject</th>
                        <th>Section</th>
                        <th>Room</th>
                        <th>Time Slot</th>
                        <th>Exam Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="scheduleTableBody">
                    @foreach($examSchedules as $schedule)
                        <tr id="schedule-row-{{ $schedule->id }}">
                            <td>{{ $schedule->subject->subject_code }} - {{ $schedule->subject->subject_description }}</td>
                            <td>{{ $schedule->section->section_name }}</td>
                            <td>{{ $schedule->room->room_name }}</td>
                            <td>{{ $schedule->time_slot }}</td>
                            <td>{{ $schedule->exam_date }}</td>
                            <td>
                                <button class="btn btn-warning btn-sm edit-schedule" data-id="{{ $schedule->id }}">Edit</button>
                                <button class="btn btn-danger btn-sm delete-schedule" data-id="{{ $schedule->id }}">Delete</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        // Handle form submission
        $('#examScheduleForm').on('submit', function (e) {
            e.preventDefault();

            const formData = $(this).serialize();
            const isUpdate = $('#schedule_id').val();

            $.ajax({
                url: isUpdate ? `/admin/exam-schedules/${isUpdate}` : '/admin/exam-schedules',
                method: isUpdate ? 'PUT' : 'POST',
                data: formData,
                success: function (response) {
                    alert(response.message);
                    location.reload();
                },
                error: function (xhr) {
                    alert('An error occurred. Please check the inputs.');
                    console.error(xhr.responseText);
                },
            });
        });

        // Handle edit
        $(document).on('click', '.edit-schedule', function () {
            const scheduleId = $(this).data('id');

            $.ajax({
                url: `/admin/exam-schedules/${scheduleId}`,
                method: 'GET',
                success: function (response) {
                    const schedule = response.schedule;

                    $('#schedule_id').val(schedule.id);
                    $('#subject_id').val(schedule.subject_id).change();
                    $('#section_id').val(schedule.section_id).change();
                    $('#room_id').val(schedule.room_id).change();
                    $('#time_slot').val(schedule.time_slot).change();
                    $('#exam_date').val(schedule.exam_date);
                },
                error: function (xhr) {
                    alert('Failed to fetch schedule details.');
                    console.error(xhr.responseText);
                },
            });
        });

        // Handle delete
        $(document).on('click', '.delete-schedule', function () {
            const scheduleId = $(this).data('id');

            if (confirm('Are you sure you want to delete this schedule?')) {
                $.ajax({
                    url: `/admin/exam-schedules/${scheduleId}`,
                    method: 'DELETE',
                    success: function (response) {
                        alert(response.message);
                        $(`#schedule-row-${scheduleId}`).remove();
                    },
                    error: function (xhr) {
                        alert('Failed to delete the schedule.');
                        console.error(xhr.responseText);
                    },
                });
            }
        });
    });
</script>
@endpush
