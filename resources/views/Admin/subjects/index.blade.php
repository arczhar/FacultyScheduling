@extends('layouts.admin')

@section('title', 'Manage Subjects')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Manage Subjects</h1>

    <!-- Add Subject Button -->
    <button type="button" id="toggleSubjectFormButton" class="btn btn-primary">Add Subject</button>

    <!-- Add/Edit Subject Form -->
    <form id="subjectForm" style="display: none;" class="mt-4">
        @csrf
        <input type="hidden" id="subject_id" name="subject_id">

        <div class="form-group">
            <label for="subject_code">Subject Code</label>
            <input type="text" class="form-control" id="subject_code" name="subject_code" required>
        </div>
        <div class="form-group">
            <label for="subject_description">Description</label>
            <input type="text" class="form-control" id="subject_description" name="subject_description" required>
        </div>
        <div class="form-group">
            <label for="type">Type</label>
            <select class="form-control" id="type" name="type" required>
                <option value="Lec">Lecture</option>
                <option value="Lab">Lab</option>
            </select>
        </div>
        <div class="form-group">
            <label for="credit_units">Credit Units</label>
            <input type="number" class="form-control" id="credit_units" name="credit_units" min="1" max="10" required>
        </div>
        <button type="button" id="submitSubjectButton" class="btn btn-primary" disabled>Add Subject</button>
        <button type="button" id="clearSubjectFormButton" class="btn btn-secondary" style="display: none;">Clear Form</button>
    </form>

    <!-- Subjects Table -->
    <h6 class="mt-4">Subjects List</h6>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Subject Code</th>
                <th>Description</th>
                <th>Type</th>
                <th>Credit Units</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="subjectTableBody">
            @foreach ($subjects as $subject)
                <tr id="subjectRow-{{ $subject->id }}">
                    <td>{{ $subject->subject_code }}</td>
                    <td>{{ $subject->subject_description }}</td>
                    <td>{{ $subject->type }}</td>
                    <td>{{ $subject->credit_units }}</td>
                    <td>
                        <button type="button" class="btn btn-warning btn-sm editSubjectButton" data-id="{{ $subject->id }}">Edit</button>
                        <button type="button" class="btn btn-danger btn-sm deleteSubjectButton" data-id="{{ $subject->id }}">Delete</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="d-flex justify-content-center mt-4">
        {{ $subjects->links('vendor.pagination.bootstrap-4') }}
    </div>
</div>

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
                <!-- Success message will be dynamically inserted here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function () {
    let initialFormData = $('#subjectForm').serialize();

    // Function to check if all required fields are filled
    function isFormValid() {
        const isCodeValid = $('#subject_code').val().trim() !== '';
        const isDescriptionValid = $('#subject_description').val().trim() !== '';
        const isTypeValid = $('#type').val().trim() !== '';
        const isUnitsValid = $('#credit_units').val().trim() !== '';
        return isCodeValid && isDescriptionValid && isTypeValid && isUnitsValid;
    }

    // Function to check if form data has changed
    function isFormChanged() {
        return $('#subjectForm').serialize() !== initialFormData;
    }

    // Monitor changes in form inputs
    $('#subjectForm input, #subjectForm select').on('input change', function () {
        const isValid = isFormValid();
        const isChanged = isFormChanged();
        $('#submitSubjectButton').prop('disabled', !(isValid && isChanged));
    });

    // Show/Hide Add Subject Form
    $('#toggleSubjectFormButton').click(function () {
        const isVisible = $('#subjectForm').is(':visible');
        if (isVisible) {
            $('#subjectForm').hide();
            $('#clearSubjectFormButton').hide();
            $('#toggleSubjectFormButton').text('Add Subject');
            $('#subjectForm')[0].reset();
            $('#submitSubjectButton').prop('disabled', true);
        } else {
            $('#subjectForm').show();
            $('#clearSubjectFormButton').show();
            $('#toggleSubjectFormButton').text('Cancel');
        }
        initialFormData = $('#subjectForm').serialize();
    });

    // Handle Add/Update Subject
    $('#submitSubjectButton').click(function (e) {
        e.preventDefault();
        const subjectId = $('#subject_id').val();
        const actionUrl = subjectId
            ? '{{ route("admin.subjects.update", ":id") }}'.replace(':id', subjectId)
            : '{{ route("admin.subjects.store") }}';

        $.ajax({
            url: actionUrl,
            method: subjectId ? 'PUT' : 'POST',
            data: $('#subjectForm').serialize(),
            success: function (response) {
                const subject = response.subject;
                const newRow = `
                    <tr id="subjectRow-${subject.id}">
                        <td>${subject.subject_code}</td>
                        <td>${subject.subject_description}</td>
                        <td>${subject.type}</td>
                        <td>${subject.credit_units}</td>
                        <td>
                            <button class="btn btn-warning btn-sm editSubjectButton" data-id="${subject.id}">Edit</button>
                            <button class="btn btn-danger btn-sm deleteSubjectButton" data-id="${subject.id}">Delete</button>
                        </td>
                    </tr>`;
                if (subjectId) {
                    $(`#subjectRow-${subject.id}`).replaceWith(newRow);
                } else {
                    $('#subjectTableBody').prepend(newRow);
                }

                $('#subjectForm')[0].reset();
                $('#subjectForm').hide();
                $('#clearSubjectFormButton').hide();
                $('#toggleSubjectFormButton').text('Add Subject');
                $('#submitSubjectButton').text('Add Subject').prop('disabled', true);
                initialFormData = $('#subjectForm').serialize();

                // Show success modal
                $('#successModal .modal-body').text(subjectId ? 'Subject updated successfully!' : 'Subject added successfully!');
                $('#successModal').modal('show');
            },
            error: function () {
                alert('An error occurred while saving the subject.');
            },
        });
    });

    // Handle Edit Subject
    $(document).on('click', '.editSubjectButton', function () {
        const subjectId = $(this).data('id');
        $.get(`{{ route('admin.subjects.index') }}/${subjectId}`, function (response) {
            const subject = response.subject;
            $('#subject_id').val(subject.id);
            $('#subject_code').val(subject.subject_code);
            $('#subject_description').val(subject.subject_description);
            $('#type').val(subject.type);
            $('#credit_units').val(subject.credit_units);
            $('#submitSubjectButton').text('Update Subject');
            $('#submitSubjectButton').prop('disabled', true);
            $('#subjectForm').show();
            $('#clearSubjectFormButton').show();
            $('#toggleSubjectFormButton').text('Cancel');
            initialFormData = $('#subjectForm').serialize();
        });
    });

    // Handle Delete Subject
    $(document).on('click', '.deleteSubjectButton', function () {
        const subjectId = $(this).data('id');

        // Confirm deletion
        if (!confirm('Are you sure you want to delete this subject?')) {
            return;
        }

        $.ajax({
            url: `{{ route("admin.subjects.index") }}/${subjectId}`,
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            },
            success: function (response) {
                if (response.success) {
                    $(`#subjectRow-${subjectId}`).remove();
                    $('#successModal .modal-body').text('Subject deleted successfully!');
                    $('#successModal').modal('show');
                } else {
                    alert('Failed to delete the subject.');
                }
            },
            error: function () {
                alert('An error occurred while deleting the subject.');
            },
        });
    });

    // Clear form handler
    $('#clearSubjectFormButton').click(function () {
        $('#subjectForm')[0].reset();
        $('#subjectForm').hide();
        $('#clearSubjectFormButton').hide();
        $('#toggleSubjectFormButton').text('Add Subject');
        $('#submitSubjectButton').text('Add Subject').prop('disabled', true);
        initialFormData = $('#subjectForm').serialize();
    });
});
</script>
@endpush
