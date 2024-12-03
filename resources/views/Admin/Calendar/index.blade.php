@extends('layouts.admin')

@section('title', 'Manage Calendar')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Manage Calendar</h1>

    <!-- Add/Edit Event Button -->
    <button type="button" id="toggleEventFormButton" class="btn btn-primary">Add Event</button>

    <!-- Add/Edit Event Form -->
    <form id="eventForm" style="display: none;" class="mt-4">
        @csrf
        <input type="hidden" id="event_id" name="event_id">

        <div class="form-group">
            <label for="title">Event Title</label>
            <input type="text" class="form-control" id="title" name="title" required>
        </div>
        <div class="form-group">
            <label for="start_date">Start Date</label>
            <input type="date" class="form-control" id="start_date" name="start_date" required>
        </div>
        <div class="form-group">
            <label for="end_date">End Date</label>
            <input type="date" class="form-control" id="end_date" name="end_date">
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
        </div>
        <button type="button" id="submitEventButton" class="btn btn-primary" disabled>Add Event</button>
        <button type="button" id="clearEventFormButton" class="btn btn-secondary" style="display: none;">Clear Form</button>
    </form>

    <!-- Events Table -->
    <h6 class="mt-4">Events List</h6>
    <table class="table table-bordered">
        <thead style="background-color: maroon; color: white;">
            <tr>
                <th>Title</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="eventTableBody">
            @foreach ($events as $event)
                <tr id="eventRow-{{ $event->id }}">
                    <td>{{ $event->title }}</td>
                    <td>{{ $event->start_date }}</td>
                    <td>{{ $event->end_date ?? 'N/A' }}</td>
                    <td>{{ $event->description }}</td>
                    <td>
                        <button type="button" class="btn btn-warning btn-sm editEventButton" data-id="{{ $event->id }}">Edit</button>
                        <button type="button" class="btn btn-danger btn-sm deleteEventButton" data-id="{{ $event->id }}">Delete</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="d-flex justify-content-center mt-4">
        {{ $events->links('vendor.pagination.bootstrap-4') }}
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
    // Initial form data for comparison
    let initialFormData = $('#eventForm').serialize();

    // Function to check if all required fields are filled
    function isFormValid() {
        const isTitleValid = $('#title').val().trim() !== '';
        const isStartDateValid = $('#start_date').val().trim() !== '';
        const isEndDateValid =
            $('#end_date').val() === '' || new Date($('#end_date').val()) >= new Date($('#start_date').val());
        return isTitleValid && isStartDateValid && isEndDateValid;
    }

    // Function to check if form data has changed
    function isFormChanged() {
        return $('#eventForm').serialize() !== initialFormData;
    }

    // Monitor changes in form inputs
    $('#eventForm input, #eventForm textarea').on('input change', function () {
        const isValid = isFormValid();
        const isChanged = isFormChanged();
        $('#submitEventButton').prop('disabled', !(isValid && isChanged));
    });

    // Show/Hide Add Event Form
    $('#toggleEventFormButton').click(function () {
        const isVisible = $('#eventForm').is(':visible');
        if (isVisible) {
            $('#eventForm').hide();
            $('#clearEventFormButton').hide();
            $('#toggleEventFormButton').text('Add Event');
            $('#eventForm')[0].reset();
            $('#submitEventButton').prop('disabled', true);
        } else {
            $('#eventForm').show();
            $('#clearEventFormButton').show();
            $('#toggleEventFormButton').text('Cancel');
        }
        initialFormData = $('#eventForm').serialize();
    });

    // Handle Add/Update Event
    $('#submitEventButton').click(function (e) {
        e.preventDefault();
        const eventId = $('#event_id').val();
        const actionUrl = eventId
            ? `/admin/calendar-events/${eventId}`
            : `/admin/calendar-events`;

        $.ajax({
            url: actionUrl,
            method: eventId ? 'PUT' : 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            },
            data: $('#eventForm').serialize(),
            success: function (response) {
                const event = response.event;
                const newRow = `
                    <tr id="eventRow-${event.id}">
                        <td>${event.title}</td>
                        <td>${event.start_date}</td>
                        <td>${event.end_date ?? 'N/A'}</td>
                        <td>${event.description}</td>
                        <td>
                            <button class="btn btn-warning btn-sm editEventButton" data-id="${event.id}">Edit</button>
                            <button class="btn btn-danger btn-sm deleteEventButton" data-id="${event.id}">Delete</button>
                        </td>
                    </tr>`;
                if (eventId) {
                    $(`#eventRow-${event.id}`).replaceWith(newRow);
                } else {
                    $('#eventTableBody').prepend(newRow);
                }

                $('#eventForm')[0].reset();
                $('#eventForm').hide();
                $('#clearEventFormButton').hide();
                $('#toggleEventFormButton').text('Add Event');
                $('#submitEventButton').text('Add Event').prop('disabled', true);
                initialFormData = $('#eventForm').serialize();

                // Show success modal
                $('#successModal .modal-body').text(eventId ? 'Event updated successfully!' : 'Event added successfully!');
                $('#successModal').modal('show');
            },
            error: function (xhr) {
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    alert(Object.values(errors).join('\n'));
                } else {
                    alert('An error occurred while saving the event.');
                }
            },
        });
    });

    // Handle Edit Event
    $(document).on('click', '.editEventButton', function () {
        const eventId = $(this).data('id');
        $.get(`/admin/calendar-events/${eventId}`, function (response) {
            const event = response.event;
            $('#event_id').val(event.id);
            $('#title').val(event.title);
            $('#start_date').val(event.start_date);
            $('#end_date').val(event.end_date);
            $('#description').val(event.description);
            $('#submitEventButton').text('Update Event');
            $('#submitEventButton').prop('disabled', true);
            $('#eventForm').show();
            $('#clearEventFormButton').show();
            $('#toggleEventFormButton').text('Cancel');
            initialFormData = $('#eventForm').serialize();
        });
    });

    // Handle Delete Event
    $(document).on('click', '.deleteEventButton', function () {
        const eventId = $(this).data('id');

        // Confirm deletion
        if (!confirm('Are you sure you want to delete this event?')) {
            return;
        }

        $.ajax({
            url: `/admin/calendar-events/${eventId}`,
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            },
            success: function (response) {
                if (response.success) {
                    $(`#eventRow-${eventId}`).remove();
                    $('#successModal .modal-body').text('Event deleted successfully!');
                    $('#successModal').modal('show');
                } else {
                    alert('Failed to delete the event.');
                }
            },
            error: function () {
                alert('An error occurred while deleting the event.');
            },
        });
    });

    // Clear form handler
    $('#clearEventFormButton').click(function () {
        $('#eventForm')[0].reset();
        $('#eventForm').hide();
        $('#clearEventFormButton').hide();
        $('#toggleEventFormButton').text('Add Event');
        $('#submitEventButton').text('Add Event').prop('disabled', true);
        initialFormData = $('#eventForm').serialize();
    });
});
</script>
@endpush
