
@extends('layouts.admin')

@section('title', 'Manage Sections')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Manage Sections</h1>

    <!-- Success Modal -->
    <div class="modal fade" id="successModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">Success</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <!-- Dynamic success message -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteConfirmationModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Confirm Delete</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this section?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteButton">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Section Button -->
    <button id="toggleSectionFormButton" class="btn btn-primary">Add Section</button>

    <!-- Section Form -->
    <form id="sectionForm" style="display: none;" class="mt-4">
        @csrf
        <input type="hidden" id="section_id" name="section_id">

        <div class="form-group">
            <label for="section_name">Section Name</label>
            <input type="text" id="section_name" name="section_name" class="form-control" required>
        </div>

        <button id="submitSectionButton" class="btn btn-primary">Add Section</button>
    </form>

    <!-- Section Table -->
    <h6 class="mt-4">Section List</h6>
    <table class="table table-bordered">
        <thead style="background-color: maroon; color: white;">
            <tr>
                <th>Section Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="sectionTableBody">
            @foreach ($sections as $section)
                <tr id="sectionRow-{{ $section->id }}">
                    <td>{{ $section->section_name }}</td>
                    <td>
                        <button class="btn btn-warning btn-sm editSectionButton" data-id="{{ $section->id }}">Edit</button>
                        <button class="btn btn-danger btn-sm deleteSectionButton" data-id="{{ $section->id }}">Delete</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="d-flex justify-content-center">
        {{ $sections->links() }}
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function () {
    let sectionId = null;

    // Toggle form visibility
    $('#toggleSectionFormButton').click(function () {
        $('#sectionForm').toggle();
        $('#sectionForm')[0].reset();
        sectionId = null;
        $('#submitSectionButton').text('Add Section');
    });

    // Add or update section
    $('#submitSectionButton').click(function (e) {
    e.preventDefault();

    const sectionId = $('#section_id').val(); // Get the section_id from the form
    const url = sectionId
        ? '{{ route("admin.sections.update", ":id") }}'.replace(':id', sectionId)
        : '{{ route("admin.sections.store") }}';

    $.ajax({
        url: url,
        method: sectionId ? 'PUT' : 'POST',
        data: $('#sectionForm').serialize(),
        success: function (response) {
            const section = response.section;

            const row = `
                <tr id="sectionRow-${section.id}">
                    <td>${section.section_name}</td>
                    <td>
                        <button class="btn btn-warning btn-sm editSectionButton" data-id="${section.id}">Edit</button>
                        <button class="btn btn-danger btn-sm deleteSectionButton" data-id="${section.id}">Delete</button>
                    </td>
                </tr>
            `;

            if (sectionId) {
                $(`#sectionRow-${section.id}`).replaceWith(row); // Update the row
            } else {
                $('#sectionTableBody').prepend(row); // Add a new row
            }

            $('#sectionForm').hide();
            $('#successModal .modal-body').text(response.message);
            $('#successModal').modal('show');
        },
        error: function (xhr) {
            alert('An error occurred.');
        }
    });
});


    // Edit section button click handler
    $(document).on('click', '.editSectionButton', function () {
    const sectionId = $(this).data('id'); // Get the section ID

    $.ajax({
        url: '{{ route("admin.sections.show", ":id") }}'.replace(':id', sectionId),
        method: 'GET',
        success: function (response) {
            if (response.success) {
                const section = response.section;

                // Populate form fields
                $('#section_id').val(section.id); // Set the hidden section_id field
                $('#section_name').val(section.section_name);

                // Update form state
                $('#sectionForm').show();
                $('#submitSectionButton').text('Update Section');
            } else {
                alert(response.message);
            }
        },
        error: function () {
            alert('Error fetching section.');
        }
    });
});



    // Delete section
    $(document).on('click', '.deleteSectionButton', function () {
        const id = $(this).data('id');
        $('#confirmDeleteButton').data('id', id);
        $('#deleteConfirmationModal').modal('show');
    });

    // Confirm delete
    $('#confirmDeleteButton').click(function () {
        const id = $(this).data('id');
        $.ajax({
            url: '{{ route("admin.sections.destroy", ":id") }}'.replace(':id', id),
            method: 'DELETE',
            success: function (response) {
                $(`#sectionRow-${id}`).remove();
                $('#deleteConfirmationModal').modal('hide');
                $('#successModal .modal-body').text(response.message);
                $('#successModal').modal('show');
            },
            error: function () {
                alert('Error deleting section.');
            }
        });
    });
});
</script>
@endpush
