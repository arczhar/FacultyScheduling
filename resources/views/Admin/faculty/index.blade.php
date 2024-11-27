@extends('layouts.admin')

@section('title', 'Manage Faculty')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Manage Faculty</h1>

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

    <!-- Add Faculty Button -->
    <button type="button" id="toggleFacultyFormButton" class="btn btn-primary">Add Faculty</button>

    <!-- Add/Edit Faculty Form -->
    <form id="facultyForm" style="display: none;" class="mt-4">
        @csrf
        <input type="hidden" id="faculty_id" name="faculty_id"> <!-- Hidden field for edit -->

        <!-- ID Number -->
        <div class="form-group">
            <label for="id_number">ID Number</label>
            <input type="text" class="form-control" id="id_number" name="id_number" required>
        </div>

        <!-- Name Fields: First Name, Middle Initial, Last Name -->
        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="first_name">First Name</label>
                <input type="text" class="form-control" id="first_name" name="first_name" required>
            </div>
            <div class="form-group col-md-4">
                <label for="middle_initial">Middle Initial</label>
                <input type="text" class="form-control" id="middle_initial" name="middle_initial" maxlength="1">
            </div>
            <div class="form-group col-md-4">
                <label for="last_name">Last Name</label>
                <input type="text" class="form-control" id="last_name" name="last_name" required>
            </div>
        </div>

        <!-- Position -->
        <div class="form-group">
            <label for="position">Position</label>
            <input type="text" class="form-control" id="position" name="position" required>
        </div>

        <button type="button" id="submitFacultyButton" class="btn btn-primary" disabled>Add Faculty</button>
        <button type="button" id="clearFormButton" class="btn btn-secondary" style="display: none;">Clear Form</button>
    </form>

    <!-- Faculty Table -->
    <h6 class="mt-4">Faculty List</h6>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID Number</th>
                <th>Name</th>
                <th>Position</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="facultyTableBody">
            @foreach ($faculties as $faculty)
                <tr id="facultyRow-{{ $faculty->id }}">
                    <td>{{ $faculty->id_number }}</td>
                    <td>{{ $faculty->first_name }} {{ $faculty->middle_initial }} {{ $faculty->last_name }}</td>
                    <td>{{ $faculty->position }}</td>
                    <td>
                        <button class="btn btn-warning btn-sm editFacultyButton" data-id="{{ $faculty->id }}">Edit</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="d-flex justify-content-center mt-4">
        {{ $faculties->links('vendor.pagination.bootstrap-4') }}
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function () {
    let initialFormData = $('#facultyForm').serialize();

    // Show/Hide Add Faculty Form
    $('#toggleFacultyFormButton').click(function () {
        const isVisible = $('#facultyForm').is(':visible');
        if (isVisible) {
            $('#facultyForm').hide();
            $('#clearFormButton').hide();
            $(this).show(); // Show Add Faculty button
            $('#facultyForm')[0].reset();
            $('#submitFacultyButton').text('Add Faculty').prop('disabled', true);
        } else {
            $('#facultyForm').show();
            $('#clearFormButton').show();
            $(this).hide(); // Hide Add Faculty button
        }
        initialFormData = $('#facultyForm').serialize();
    });

    // Function to check if form has changed
    function isFormChanged() {
        return $('#facultyForm').serialize() !== initialFormData;
    }

    // Monitor changes in form inputs
    $('#facultyForm input, #facultyForm select').on('input', function () {
        $('#submitFacultyButton').prop('disabled', !isFormChanged());
    });

    // Handle Add/Update Faculty
    $('#submitFacultyButton').click(function (e) {
        e.preventDefault();
        const facultyId = $('#faculty_id').val();
        const actionUrl = facultyId
            ? '{{ route("admin.faculty.update", ":id") }}'.replace(':id', facultyId)
            : '{{ route("admin.faculty.store") }}';

        $.ajax({
            url: actionUrl,
            method: facultyId ? 'PUT' : 'POST',
            data: $('#facultyForm').serialize(),
            success: function (response) {
                if (response.success) {
                    const faculty = response.faculty;

                    // Update existing row or prepend new row
                    const newRow = `
                        <tr id="facultyRow-${faculty.id}">
                            <td>${faculty.id_number}</td>
                            <td>${faculty.first_name} ${faculty.middle_initial || ''} ${faculty.last_name}</td>
                            <td>${faculty.position || 'N/A'}</td>
                            <td>
                                <button class="btn btn-warning btn-sm editFacultyButton" data-id="${faculty.id}">Edit</button>
                            </td>
                        </tr>`;
                    if (facultyId) {
                        $(`#facultyRow-${faculty.id}`).replaceWith(newRow);
                    } else {
                        $('#facultyTableBody').prepend(newRow);
                    }

                    // Reset the form and hide it
                    $('#facultyForm')[0].reset();
                    $('#facultyForm').hide();
                    $('#clearFormButton').hide();
                    $('#toggleFacultyFormButton').show(); // Show Add Faculty button
                    initialFormData = $('#facultyForm').serialize();

                    // Show success modal
                    $('#validationModal .modal-body').text('Faculty saved successfully!');
                    $('#validationModal').modal('show');
                }
            }
        });
    });

    // Handle Edit Faculty
    $(document).on('click', '.editFacultyButton', function () {
        const facultyId = $(this).data('id');
        $.ajax({
            url: '{{ route("admin.faculty.show", ":id") }}'.replace(':id', facultyId),
            method: 'GET',
            success: function (response) {
                const faculty = response.faculty;

                // Populate form fields
                $('#faculty_id').val(faculty.id);
                $('#id_number').val(faculty.id_number);
                $('#first_name').val(faculty.first_name);
                $('#last_name').val(faculty.last_name);
                $('#middle_initial').val(faculty.middle_initial);
                $('#position').val(faculty.position);

                // Show form for editing
                $('#facultyForm').show();
                $('#clearFormButton').show();
                $('#toggleFacultyFormButton').hide(); // Hide Add Faculty button
                $('#submitFacultyButton').text('Update Faculty').prop('disabled', true);

                initialFormData = $('#facultyForm').serialize();
            },
            error: function () {
                $('#validationModal .modal-body').text('Error fetching faculty data.');
                $('#validationModal').modal('show');
            }
        });
    });

    // Clear form handler
    $('#clearFormButton').click(function () {
        $('#facultyForm')[0].reset();
        $('#facultyForm').hide();
        $(this).hide();
        $('#toggleFacultyFormButton').show(); // Show Add Faculty button
        $('#submitFacultyButton').text('Add Faculty').prop('disabled', true);
        initialFormData = $('#facultyForm').serialize();
    });
});
</script>
@endpush
