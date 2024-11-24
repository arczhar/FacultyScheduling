<select id="faculty_id">
    <option value="1">Faculty 1</option>
    <option value="2">Faculty 2</option>
</select>

<input type="text" id="position" readonly />

<table>
    <thead>
        <tr>
            <th>Subject Code</th>
            <th>Description</th>
            <th>Room</th>
            <th>Start Time</th>
            <th>End Time</th>
        </tr>
    </thead>
    <tbody id="schedule_table_body"></tbody>
</table>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $('#faculty_id').change(function () {
        var facultyId = $(this).val();
        $.ajax({
            url: '/get-faculty-details/' + facultyId,
            method: 'GET',
            success: function (response) {
                console.log('API Response:', response);

                // Update Position
                $('#position').val(response.position);

                // Update Table
                var scheduleHtml = '';
                $.each(response.schedules, function (index, schedule) {
                    scheduleHtml += '<tr>' +
                        '<td>' + schedule.subject_code + '</td>' +
                        '<td>' + schedule.subject_description + '</td>' +
                        '<td>' + schedule.room + '</td>' +
                        '<td>' + schedule.start_time + '</td>' +
                        '<td>' + schedule.end_time + '</td>' +
                        '</tr>';
                });
                $('#schedule_table_body').html(scheduleHtml);
            }
        });
    });
</script>
