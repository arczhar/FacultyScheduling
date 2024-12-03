@extends('layouts.admin')
    <style>
    #start_time, #end_time {
    width: 100%; /* Ensure the dropdown fills its container */
    height: 38px; /* Match the input field height */
    font-size: 16px; /* Match font size with other form controls */
    padding: 6px 12px; /* Padding for better text visibility */
    border-radius: 4px; /* Rounded corners for consistency */
    border: 1px solid #ced4da; /* Same border style as input fields */
    background-color: #fff; /* White background for clarity */
}

</style>


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
                <thead style="background-color: maroon; color: white;">
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
                        <select id="start_time" name="start_time" class="form-control" required>
                            <option value="">Select Start Time</option>
                            <option value="07:00">07:00</option>
                            <option value="08:00">08:00</option>
                            <option value="09:00">09:00</option>
                            <option value="10:00">10:00</option>
                            <option value="11:00">11:00</option>
                            <option value="12:00">12:00</option>
                            <option value="13:00">13:00</option>
                            <option value="14:00">14:00</option>
                            <option value="15:00">15:00</option>
                            <option value="16:00">16:00</option>
                            <option value="17:00">17:00</option>
                            <option value="18:00">18:00</option>
                            <option value="19:00">19:00</option>
                            <option value="20:00">20:00</option>

                            <!-- Add other time intervals -->
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="end_time">End Time</label>
                        <select id="end_time" name="end_time" class="form-control" required>
                            <option value="">Select End Time</option>
                            <!-- Options dynamically populated -->
                        </select>
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
            <button type="button" id="update_schedule_button" class="btn btn-warning" style="display: none;">Update Schedule</button>

            <a href="{{ route('admin.schedules.index') }}" class="btn btn-secondary">Back to List</a>

        </form>
    </div>
@endsection

@push('scripts')
<script>
$(document).ready(function () {
    // Set up CSRF token for all AJAX requests
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        },
    });

    let scheduleIdToDelete = null;

    // Handle delete button click
    $(document).on('click', '.delete-schedule', function () {
        scheduleIdToDelete = $(this).data('id');
        $('#deleteConfirmationModal').modal('show');
    });

    // Handle confirm delete button click
    $('#confirmDeleteButton').click(function () {
        if (scheduleIdToDelete) {
            $.ajax({
                url: `/admin/schedules/${scheduleIdToDelete}`,
                method: 'DELETE',
                success: function (response) {
                    if (response.success) {
                        $(`#schedule-row-${scheduleIdToDelete}`).remove();
                        $('#deleteConfirmationModal').modal('hide');
                        showModal('Success', response.message);
                    } else {
                        showModal('Error', response.message);
                    }
                },
                error: function (xhr) {
                    console.error('Error deleting schedule:', xhr.responseText);
                    showModal('Error', 'An error occurred while deleting the schedule.');
                },
            });
        }
    });

    // Handle faculty selection change
    $('#faculty_id').change(function () {
        const facultyId = $(this).val();
        if (facultyId) {
            $.ajax({
                url: `/get-faculty-details/${facultyId}`,
                method: 'GET',
                success: function (response) {
                    if (response) {
                        $('#position').val(response.position);
                        populateScheduleTable(response.schedules);
                    }
                },
                error: function (xhr) {
                    console.error('Error fetching faculty details:', xhr.responseText);
                },
            });
        } else {
            $('#position').val('');
            $('#schedule_table_body').html('<tr><td colspan="8" class="text-center">No schedule available</td></tr>');
        }
    });

    // Handle subject selection change
    $('#subject_code').change(function () {
        const subjectId = $(this).val();
        if (subjectId) {
            $.ajax({
                url: `/get-subject-details/${subjectId}`,
                method: 'GET',
                success: function (response) {
                    if (response.success) {
                        $('#subject_description').val(response.subject.subject_description);
                        $('#subject_type').val(response.subject.type);
                        $('#subject_units').val(response.subject.credit_units);
                    } else {
                        alert('Subject not found!');
                    }
                },
                error: function (xhr) {
                    console.error('Error fetching subject details:', xhr.responseText);
                },
            });
        } else {
            resetSubjectFields();
        }
    });

    // Handle Add/Update Schedule button click
    $(document).on('click', '#add_schedule_button, #update_schedule_button', function (e) {
        e.preventDefault();

        const buttonId = $(this).attr('id');
        const isUpdate = buttonId === 'update_schedule_button';
        const scheduleId = $('#addScheduleForm').data('schedule-id'); // Retrieve schedule ID for updates

        if (isUpdate && !scheduleId) {
            alert('Schedule ID is missing for update!');
            return;
        }

        const ajaxOptions = {
            url: isUpdate ? `/admin/schedules/${scheduleId}` : '/admin/schedules',
            method: isUpdate ? 'PUT' : 'POST',
            data: $('#addScheduleForm').serialize(),
            success: function (response) {
                if (response.success) {
                    if (isUpdate) {
                        updateScheduleRow(scheduleId, response.schedule); // Update row
                    } else {
                        appendScheduleRow(response.schedule); // Add new row
                    }
                    resetScheduleFields();
                    showModal('Success', response.message);

                    if (isUpdate) {
                        $('#update_schedule_button').text('Add Schedule').attr('id', 'add_schedule_button');
                        $('#addScheduleForm').removeData('schedule-id'); // Remove schedule ID
                    }
                } else {
                    showModal('Error', response.message);
                }
            },
            error: function (xhr) {
                console.error('Error:', xhr.responseText);
                showModal('Error', 'An error occurred while processing the schedule.');
            },
        };

        // Execute AJAX request
        $.ajax(ajaxOptions);
    });

    // Handle edit button click
    $(document).on('click', '.edit-schedule', function () {
        const scheduleId = $(this).data('id'); // Retrieve schedule ID
        console.log('Schedule ID:', scheduleId);

        if (!scheduleId) {
            alert('Schedule ID is missing!');
            return;
        }

        $.ajax({
            url: `/admin/schedules/${scheduleId}`,
            method: 'GET',
            success: function (response) {
                if (response.success) {
                    const schedule = response.schedule;

                    // Populate form fields
                    $('#subject_code').val(schedule.subject_id).change();
                    $('#subject_description').val(schedule.subject_description);
                    $('#subject_type').val(schedule.type);
                    $('#subject_units').val(schedule.units);
                    $('#day').val(schedule.day);
                    $('#start_time').val(schedule.start_time);
                    $('#end_time').val(schedule.end_time);
                    $('#room_id').val(schedule.room_id);

                    // Attach schedule ID for updates
                    $('#addScheduleForm').data('schedule-id', scheduleId);

                    // Switch to update mode
                    $('#add_schedule_button').text('Update Schedule').attr('id', 'update_schedule_button');
                } else {
                    alert('Failed to fetch schedule details.');
                }
            },
            error: function (xhr) {
                console.error('Error fetching schedule details:', xhr.responseText);
                alert('An error occurred while fetching schedule details.');
            },
        });
    });

    // Populate faculty schedule table
    function populateScheduleTable(schedules) {
        let html = '';
        if (schedules.length === 0) {
            html = `<tr><td colspan="8" class="text-center">No schedule available</td></tr>`;
        } else {
            schedules.forEach(schedule => {
                html += `
                    <tr id="schedule-row-${schedule.id}">
                        <td>${schedule.subject_code}</td>
                        <td>${schedule.subject_description}</td>
                        <td>${schedule.type}</td>
                        <td>${schedule.units}</td>
                        <td>${schedule.day}</td>
                        <td>${schedule.room}</td>
                        <td>${schedule.start_time} - ${schedule.end_time}</td>
                        <td>
                            <button class="btn btn-warning btn-sm edit-schedule" data-id="${schedule.id}">Edit</button>
                            <button class="btn btn-danger btn-sm delete-schedule" data-id="${schedule.id}">Delete</button>
                        </td>
                    </tr>`;
            });
        }
        $('#schedule_table_body').html(html);
    }

    // Reset form fields
    function resetScheduleFields() {
        $('#subject_code').val('');
        $('#subject_description').val('');
        $('#subject_type').val('');
        $('#subject_units').val('');
        $('#day').val('Monday');
        $('#start_time').val('');
        $('#end_time').val('');
        $('#room_id').val('');
    }

    // Show modal with message
    function showModal(title, message) {
        $('#validationModal .modal-title').text(title);
        $('#validationModal .modal-body').text(message);
        $('#validationModal').modal('show');
    }

    // Dynamic end time options based on start time
    $('#start_time').change(function () {
        const startTime = $(this).val();
        console.log('Selected Start Time:', startTime);

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

    // Disable end time initially
    $('#end_time').prop('disabled', true);

    function appendScheduleRow(schedule) {
        const row = `
            <tr id="schedule-row-${schedule.id}">
                <td>${schedule.subject_code || 'N/A'}</td>
                <td>${schedule.subject_description || 'N/A'}</td>
                <td>${schedule.type || 'N/A'}</td>
                <td>${schedule.units || 'N/A'}</td>
                <td>${schedule.day || 'N/A'}</td>
                <td>${schedule.room || 'N/A'}</td>
                <td>${schedule.start_time || ''} - ${schedule.end_time || ''}</td>
                <td>
                    <button class="btn btn-warning btn-sm edit-schedule" data-id="${schedule.id}">Edit</button>
                    <button class="btn btn-danger btn-sm delete-schedule" data-id="${schedule.id}">Delete</button>
                </td>
            </tr>`;
        $('#schedule_table_body').append(row);
    }

    function updateScheduleRow(scheduleId, schedule) {
        const row = `
            <tr id="schedule-row-${scheduleId}">
                <td>${schedule.subject_code || 'N/A'}</td>
                <td>${schedule.subject_description || 'N/A'}</td>
                <td>${schedule.type || 'N/A'}</td>
                <td>${schedule.units || 'N/A'}</td>
                <td>${schedule.day || 'N/A'}</td>
                <td>${schedule.room || 'N/A'}</td>
                <td>${schedule.start_time || ''} - ${schedule.end_time || ''}</td>
                <td>
                    <button class="btn btn-warning btn-sm edit-schedule" data-id="${scheduleId}">Edit</button>
                    <button class="btn btn-danger btn-sm delete-schedule" data-id="${scheduleId}">Delete</button>
                </td>
            </tr>`;
        $(`#schedule-row-${scheduleId}`).replaceWith(row);
    }
});

</script>
@endpush
