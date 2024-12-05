@extends('layouts.admin')

@section('title', 'Manage Faculty')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Manage Faculty</h1>

    <!-- Success Modal -->
    <div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="successModalLabel">Success</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Dynamic success or error message will be inserted here -->
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
                    Are you sure you want to delete this faculty member?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteButton">Delete</button>
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
        <thead style="background-color: maroon; color: white;">
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
                        <button class="btn btn-danger btn-sm deleteFacultyButton" data-id="{{ $faculty->id }}">Delete</button>
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

    // Function to check if form has changed
    function isFormChanged() {
        return $('#facultyForm').serialize() !== initialFormData;
    }

    // Show/Hide Add Faculty Form
    $('#toggleFacultyFormButton').click(function () {
        const isVisible = $('#facultyForm').is(':visible');
        if (isVisible) {
            $('#facultyForm').hide();
            $('#clearFormButton').hide();
            $(this).show();
            $('#facultyForm')[0].reset();
            $('#submitFacultyButton').text('Add Faculty').prop('disabled', true);
        } else {
            $('#facultyForm').show();
            $('#clearFormButton').show();
            $(this).hide();
        }
        initialFormData = $('#facultyForm').serialize();
    });

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

                    // Update or add new row in the table
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

                    // Reset the form
                    $('#facultyForm')[0].reset();
                    $('#facultyForm').hide();
                    $('#clearFormButton').hide();
                    $('#toggleFacultyFormButton').show();
                    initialFormData = $('#facultyForm').serialize();

                    // Show success modal with green header
                    $('#successModal .modal-header')
                        .removeClass('bg-danger')
                        .addClass('bg-success');
                    $('#successModal .modal-title').text('Success');
                    $('#successModal .modal-body').text(response.message);
                    $('#successModal').modal('show');
                }
            },
            error: function (xhr) {
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    const errorMessages = Object.values(errors).join('<br>');

                    // Show error modal with red header
                    $('#successModal .modal-header')
                        .removeClass('bg-success')
                        .addClass('bg-danger');
                    $('#successModal .modal-title').text('Error');
                    $('#successModal .modal-body').html(errorMessages);
                    $('#successModal').modal('show');
                } else {
                    alert('An unexpected error occurred.');
                }
            },
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

                $('#faculty_id').val(faculty.id);
                $('#id_number').val(faculty.id_number);
                $('#first_name').val(faculty.first_name);
                $('#last_name').val(faculty.last_name);
                $('#middle_initial').val(faculty.middle_initial);
                $('#position').val(faculty.position);

                $('#facultyForm').show();
                $('#clearFormButton').show();
                $('#toggleFacultyFormButton').hide();
                $('#submitFacultyButton').text('Update Faculty').prop('disabled', true);

                initialFormData = $('#facultyForm').serialize();
            },
            error: function () {
                $('#successModal .modal-body').text('Error fetching faculty data.');
                $('#successModal').modal('show');
            }
        });
    });

    // Clear form handler
    $('#clearFormButton').click(function () {
        $('#facultyForm')[0].reset();
        $('#facultyForm').hide();
        $(this).hide();
        $('#toggleFacultyFormButton').show();
        $('#submitFacultyButton').text('Add Faculty').prop('disabled', true);
        initialFormData = $('#facultyForm').serialize();
    });

    // Reset modal styles on hide
    $('#successModal').on('hidden.bs.modal', function () {
        $('#successModal .modal-header').removeClass('bg-danger').addClass('bg-success');
        $('#successModal .modal-title').text('Success');
    });

    // Handle Delete Faculty
    $(document).ready(function () {
    let facultyIdToDelete = null;

    // Show delete confirmation modal
    $(document).on('click', '.deleteFacultyButton', function () {
        facultyIdToDelete = $(this).data('id');
        $('#deleteConfirmationModal').modal('show');
    });

    // Confirm delete action
    $('#confirmDeleteButton').click(function () {
        if (facultyIdToDelete) {
            $.ajax({
                url: '{{ route("admin.faculty.destroy", ":id") }}'.replace(':id', facultyIdToDelete),
                method: 'DELETE',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'), // CSRF token
                },
                success: function (response) {
                    if (response.success) {
                        // Remove the row from the table
                        $(`#facultyRow-${facultyIdToDelete}`).remove();

                        // Hide the modal
                        $('#deleteConfirmationModal').modal('hide');

                        // Show success modal with green header
                        $('#successModal .modal-header')
                            .removeClass('bg-danger')
                            .addClass('bg-success');
                        $('#successModal .modal-title').text('Success');
                        $('#successModal .modal-body').text(response.message);
                        $('#successModal').modal('show');
                    } else {
                        // Show error modal with red header
                        $('#successModal .modal-header')
                            .removeClass('bg-success')
                            .addClass('bg-danger');
                        $('#successModal .modal-title').text('Error');
                        $('#successModal .modal-body').text(response.message);
                        $('#successModal').modal('show');
                    }
                },
                error: function () {
                    alert('An error occurred while trying to delete the faculty. Please try again.');
                },
            });
        }
    });
});

});

</script>
@endpush
