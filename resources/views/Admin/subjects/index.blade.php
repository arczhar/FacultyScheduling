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
@endsection

@push('scripts')
<script>
$(document).ready(function () {
    let initialFormData = $('#subjectForm').serialize();

    // Show/Hide Add Subject Form
    $('#toggleSubjectFormButton').click(function () {
        const isVisible = $('#subjectForm').is(':visible');
        if (isVisible) {
            $('#subjectForm').hide();
            $('#clearSubjectFormButton').hide();
            $('#toggleSubjectFormButton').text('Add Subject');
            $('#subjectForm')[0].reset();
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
            $('#subjectForm').show();
            $('#toggleSubjectFormButton').text('Cancel');
        });
    });

    // Handle Delete Subject
    $(document).on('click', '.deleteSubjectButton', function () {
        const subjectId = $(this).data('id');
        $.ajax({
            url: '{{ route("admin.subjects.index") }}/' + subjectId,
            method: 'DELETE',
            success: function () {
                $(`#subjectRow-${subjectId}`).remove();
            },
        });
    });
});
</script>
@endpush
