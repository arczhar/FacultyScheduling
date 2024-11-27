
<!-- Add Schedule Modal -->
<div class="modal fade" id="addScheduleModal" tabindex="-1" role="dialog" aria-labelledby="addScheduleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addScheduleModalLabel">Add Schedule</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
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
                            <input type="text" class="form-control" id="semester" name="semester" value="2nd Sem" readonly>
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
                    <div id="pagination_controls" class="text-center mt-3">
                        <!-- Pagination buttons will be populated here -->
                    </div>

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

                    <!-- Validation Messages -->
                    <div class="alert alert-danger d-none" id="validation_messages"></div>

                    <!-- Buttons -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-warning" id="check_conflict">Check Conflict</button>
                        <button type="submit" class="btn btn-primary">Save Schedule</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>





@endsection

@push('scripts')
<script>
$(document).ready(function () {
    // Fetch Faculty Details
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
                    console.error('Error fetching faculty details:', xhr.responseText);
                }
            });
        } else {
            $('#position').val('');
            $('#schedule_table_body').html('');
        }
    });

    // Fetch Subject Details
    $('#subject_code').change(function () {
        var subjectId = $(this).val();
        if (subjectId) {
            $.ajax({
                url: '/get-subject-details/' + subjectId,
                method: 'GET',
                success: function (response) {
                    $('#subject_description').val(response.subject_description);
                    $('#subject_units').val(response.units);
                    $('#subject_type').val(response.type);
                },
                error: function (xhr) {
                    console.error('Error fetching subject details:', xhr.responseText);
                }
            });
        } else {
            $('#subject_description, #subject_units, #subject_type').val('');
        }
    });

    // Check Conflict and Save Schedule
    $('#check_conflict').click(function () {
        var formData = $('#addScheduleForm').serialize();
        $.ajax({
            url: '/check-schedule-conflict', // Your route for conflict checking and saving
            method: 'POST',
            data: formData,
            success: function (response) {
                if (response.conflict) {
                    // Display conflict validation message
                    $('#validation_messages').removeClass('d-none').text(response.message);
                } else {
                    // Hide validation messages
                    $('#validation_messages').addClass('d-none');
                    
                    // Add the new schedule to the table
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

                    // Optionally reset the form fields after successful save
                    $('#addScheduleForm')[0].reset();
                    alert('Schedule saved successfully!');
                }
            },
            error: function (xhr) {
                console.error('Error:', xhr.responseText);
            }
        });
    });

    function populateScheduleTable(schedules) {
        var scheduleHtml = '';
        $.each(schedules, function (index, schedule) {
            scheduleHtml += '<tr>' +
                '<td>' + schedule.subject_code + '</td>' +
                '<td>' + schedule.subject_description + '</td>' +
                '<td>' + schedule.type + '</td>' +
                '<td>' + schedule.units + '</td>' +
                '<td>' + schedule.day + '</td>' +
                '<td>' + schedule.room + '</td>' +
                '<td>' + schedule.start_time + ' - ' + schedule.end_time + '</td>' +
                '</tr>';
        });
        $('#schedule_table_body').html(scheduleHtml);
    }
});


</script>
@endpush
