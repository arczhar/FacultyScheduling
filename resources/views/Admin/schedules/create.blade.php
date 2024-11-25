@extends('layouts.admin')

@section('title', 'Add Schedule')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Add Schedule</h1>

    <!-- Validation Modal -->
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

    <form action="{{ route('admin.schedules.store') }}" method="POST" id="addScheduleForm">
    @csrf
        <!-- Faculty Selection -->
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="faculty_id">Faculty</label>
                <select class="form-control" id="faculty_id" name="faculty_id" required>
                    <option value="">Select Faculty</option>
                    @foreach($faculties as $faculty)
                        <option value="{{ $faculty->id }}">{{ $faculty->first_name }} {{ $faculty->last_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-6">
                <label for="position">Position</label>
                <input type="text" class="form-control" id="position" name="position" readonly>
            </div>
        </div>

       <!-- Semester and School Year -->
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="semester">Semester</label>
                <select class="form-control" id="semester" name="semester" required>
                    <option value="1st Semester" {{ old('semester') == '1st Semester' ? 'selected' : '' }}>1st Semester</option>
                    <option value="2nd Semester" {{ old('semester') == '2nd Semester' ? 'selected' : '' }}>2nd Semester</option>
                    <option value="Summer" {{ old('semester') == 'Summer' ? 'selected' : '' }}>Summer</option>
                </select>
            </div>
    <div class="form-group col-md-6">
        <label for="school_year">School Year</label>
        <input type="text" class="form-control" id="school_year" name="school_year" value="2024-2025" readonly>
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
                </tr>
            </thead>
            <tbody id="schedule_table_body">
                <!-- Auto-filled rows will appear here -->
            </tbody>
        </table>

        <!-- Subject Selection -->
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="subject_code">Subject Code</label>
                <select class="form-control" id="subject_code" name="subject_id" required>
                    <option value="">Select Subject</option>
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}">{{ $subject->subject_code }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-6">
                <label for="subject_description">Subject Description</label>
                <input type="text" class="form-control" id="subject_description" name="subject_description" readonly>
            </div>
        </div>

        <!-- Additional Subject Details -->
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="subject_type">Subject Type</label>
                <input type="text" class="form-control" id="subject_type" name="subject_type" readonly>
            </div>
            <div class="form-group col-md-6">
                <label for="subject_units">Subject Units</label>
                <input type="text" class="form-control" id="subject_units" name="subject_units" readonly>
            </div>
        </div>

        <!-- Schedule Details -->
        <div class="form-row">
            <div class="form-group col-md-3">
                <label for="day">Day</label>
                <select class="form-control" id="day" name="day" required>
                    <option value="Monday">Monday</option>
                    <option value="Tuesday">Tuesday</option>
                    <option value="Wednesday">Wednesday</option>
                    <option value="Thursday">Thursday</option>
                    <option value="Friday">Friday</option>
                </select>
            </div>
            <div class="form-group col-md-3">
                <label for="start_time">Start Time</label>
                <input type="time" class="form-control" id="start_time" name="start_time" required>
            </div>
            <div class="form-group col-md-3">
                <label for="end_time">End Time</label>
                <input type="time" class="form-control" id="end_time" name="end_time" required>
            </div>
            <div class="form-group col-md-3">
                <label for="room_id">Room</label>
                <select class="form-control" id="room_id" name="room_id" required>
                    <option value="">Select Room</option>
                    @foreach($rooms as $room)
                        <option value="{{ $room->id }}">{{ $room->room_name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Buttons -->
     
        <button type="button" id="add_schedule_button" class="btn btn-primary">Add Schedule</button>
        <a href="{{ route('admin.schedules.index') }}" class="btn btn-secondary">Back to List</a>

    </form>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#faculty_id').change(function () {
        var facultyId = $(this).val();
        if (facultyId) {
            $.ajax({
                url: '/get-faculty-details/' + facultyId,
                method: 'GET',
                success: function (response) {
                    $('#position').val(response.position);
                    populateScheduleTable(response.schedules);
                },
                error: function (xhr) {
                    console.error('Error:', xhr.responseText);
                }
            });
        } else {
            $('#position').val('');
            $('#schedule_table_body').html('');
        }
    });

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
                    console.error('Error:', xhr.responseText);
                }
            });
        }
    });

    // Add Schedule Button Click
    $('#add_schedule_button').click(function (e) {
        e.preventDefault();

        $.ajax({
            url: '/check-schedule-conflict',
            method: 'POST',
            data: $('#addScheduleForm').serialize(),
            success: function (response) {
                if (response.conflict) {
                    // Show validation modal
                    $('#validationModal .modal-body').text(response.message);
                    $('#validationModal').modal('show');
                } else {
                    // Add the schedule to the faculty schedule table dynamically
                    var newRow = `
                        <tr>
                            <td>${response.schedule.subject_code}</td>
                            <td>${response.schedule.subject_description}</td>
                            <td>${response.schedule.type}</td>
                            <td>${response.schedule.units}</td>
                            <td>${response.schedule.day}</td>
                            <td>${response.schedule.room}</td>
                            <td>${response.schedule.start_time} - ${response.schedule.end_time}</td>
                        </tr>`;
                    $('#schedule_table_body').append(newRow);

                    // Reset only the subject-related fields
                    $('#subject_code').val('');
                    $('#subject_description').val('');
                    $('#subject_type').val('');
                    $('#subject_units').val('');
                    $('#day').val('Monday');
                    $('#start_time').val('');
                    $('#end_time').val('');
                    $('#room_id').val('');

                    // Show success modal
                    $('#validationModal .modal-body').text('Schedule added successfully!');
                    $('#validationModal').modal('show');
                }
            },
            error: function (xhr) {
                console.error('Error:', xhr.responseText);
            }
        });
    });

    function populateScheduleTable(schedules) {
        var html = '';
        schedules.forEach(function (schedule) {
            html += `
                <tr>
                    <td>${schedule.subject_code}</td>
                    <td>${schedule.subject_description}</td>
                    <td>${schedule.type}</td>
                    <td>${schedule.units}</td>
                    <td>${schedule.day}</td>
                    <td>${schedule.room}</td>
                    <td>${schedule.start_time} - ${schedule.end_time}</td>
                </tr>`;
        });
        $('#schedule_table_body').html(html);
    }
});

</script>
@endpush
