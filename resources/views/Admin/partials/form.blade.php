<!-- resources/views/admin/partials/form.blade.php -->
<div class="modal-body">
    <!-- Faculty Name -->
    <div class="form-group">
        <label for="faculty_id">Faculty Name</label>
        <div class="input-group">
            <input type="text" id="faculty_name" class="form-control" name="faculty_name" placeholder="Select Faculty" readonly required>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#facultyModal">Select</button>
        </div>
    </div>

    <!-- Faculty Position -->
    <div class="form-group">
        <label for="faculty_position">Position</label>
        <input type="text" class="form-control" id="faculty_position" name="faculty_position" placeholder="Faculty Position" readonly required>
    </div>

    <!-- School Year -->
    <div class="form-group">
        <label for="school_year">School Year</label>
        <input type="text" class="form-control" id="school_year" name="school_year" value="2024-2025" readonly required>
    </div>

    <!-- Semester -->
    <div class="form-group">
        <label for="semester">Semester</label>
        <select class="form-control" id="semester" name="semester" required>
            <option value="1st Semester">1st Semester</option>
            <option value="2nd Semester">2nd Semester</option>
        </select>
    </div>

    <!-- Subject Code -->
    <div class="form-group">
        <label for="subject_code">Subject Code</label>
        <div class="input-group">
            <input type="text" id="subject_code" class="form-control" name="subject_code" placeholder="Select Subject" readonly required>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#subjectModal">Select</button>
        </div>
    </div>

    <!-- Check for Conflicts -->
    <button type="button" class="btn btn-warning" id="check_conflict">Check Conflict</button>

    <!-- Schedule List -->
    <h5 class="mt-4">Faculty's Schedule</h5>
    <table class="table table-bordered" id="faculty_schedule_table">
        <thead>
            <tr>
                <th>Subject Code</th>
                <th>Description</th>
                <th>Units</th>
                <th>Days</th>
                <th>Room</th>
                <th>Section</th>
            </tr>
        </thead>
        <tbody>
            <!-- Rows dynamically populated by JavaScript after selecting the faculty -->
        </tbody>
    </table>

    <!-- Add New Schedule for Selected Subject -->
    <div class="form-group mt-3">
        <label for="subject_details">Add Subject</label>
        <div class="row">
            <div class="col-6">
                <input type="text" id="subject_details" class="form-control" placeholder="Subject Code" readonly required>
            </div>
            <div class="col-6">
                <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#subjectModal">Add Subject</button>
            </div>
        </div>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
    <button type="submit" class="btn btn-success">Save Schedule</button>
</div>
    