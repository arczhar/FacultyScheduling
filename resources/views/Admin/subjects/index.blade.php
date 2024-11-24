<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fetch Subject Details</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h3>Fetch Subject Details</h3>

        <!-- Dropdown to Select Subject -->
        <div class="form-group">
            <label for="subject_id">Select Subject:</label>
            <select id="subject_id" class="form-control">
                <option value="">-- Select Subject --</option>
                @foreach ($subjects as $subject)
                    <option value="{{ $subject->id }}">{{ $subject->subject_code }}</option>
                @endforeach
            </select>
        </div>

        <!-- Display Subject Details -->
        <div class="form-group">
            <label for="subject_description">Subject Description:</label>
            <input type="text" id="subject_description" class="form-control" readonly>
        </div>
        <div class="form-group">
            <label for="subject_type">Subject Type:</label>
            <input type="text" id="subject_type" class="form-control" readonly>
        </div>
        <div class="form-group">
            <label for="credit_units">Credit Units:</label>
            <input type="text" id="credit_units" class="form-control" readonly>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        $(document).ready(function () {
            $('#subject_id').change(function () {
                var subjectId = $(this).val();

                if (subjectId) {
                    // Fetch subject details using AJAX
                    $.ajax({
                        url: '/get-subject-details/' + subjectId,
                        method: 'GET',
                        success: function (response) {
                            // Populate input fields with subject details
                            $('#subject_description').val(response.subject_description || '');
                            $('#subject_type').val(response.subject_type || '');
                            $('#credit_units').val(response.credit_units || '');
                        },
                        error: function (xhr) {
                            console.error('Error fetching subject details:', xhr.responseText);
                            alert('Failed to fetch subject details.');
                        }
                    });
                } else {
                    // Reset input fields if no subject is selected
                    $('#subject_description, #subject_type, #credit_units').val('');
                }
            });
        });
    </script>
</body>
</html>
