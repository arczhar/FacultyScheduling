@extends('layouts.admin')

@section('title', 'Manage Rooms')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Manage Rooms</h1>

    <!-- Add Room Button -->
    <button type="button" id="toggleRoomFormButton" class="btn btn-primary">Add Room</button>
    
    <!-- Add/Edit Room Form -->
    <form id="roomForm" style="display: none;" class="mt-4">
        @csrf
        <input type="hidden" id="room_id" name="room_id">

        <div class="form-group">
            <label for="room_name">Room Name</label>
            <input type="text" class="form-control" id="room_name" name="room_name" required>
        </div>
        <button type="button" id="submitRoomButton" class="btn btn-primary" disabled>Add Room</button>
        <button type="button" id="clearRoomFormButton" class="btn btn-secondary" style="display: none;">Clear Form</button>
    </form>

    <!-- Rooms Table -->
    <h6 class="mt-4">Room List</h6>
    <table class="table table-bordered">
        <thead style="background-color: maroon; color: white;">
            <tr>
                <th>Room Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="roomTableBody">
            @foreach ($rooms as $room)
                <tr id="roomRow-{{ $room->id }}">
                    <td>{{ $room->room_name }}</td>
                    <td>
                        <button type="button" class="btn btn-warning btn-sm editRoomButton" data-id="{{ $room->id }}">Edit</button>
                        <button type="button" class="btn btn-danger btn-sm deleteRoomButton" data-id="{{ $room->id }}">Delete</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="d-flex justify-content-center mt-4">
        {{ $rooms->links('vendor.pagination.bootstrap-4') }}
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
    let initialFormData = $('#roomForm').serialize();

    // Function to check if form is valid
    function isFormValid() {
        const isRoomNameValid = $('#room_name').val().trim() !== '';
        return isRoomNameValid;
    }

    // Function to check if form data has changed
    function isFormChanged() {
        return $('#roomForm').serialize() !== initialFormData;
    }

    // Monitor changes in form inputs
    $('#roomForm input').on('input change', function () {
        const isValid = isFormValid();
        const isChanged = isFormChanged();
        $('#submitRoomButton').prop('disabled', !(isValid && isChanged));
    });

    // Show/Hide Add Room Form
    $('#toggleRoomFormButton').click(function () {
        const isVisible = $('#roomForm').is(':visible');
        if (isVisible) {
            $('#roomForm').hide();
            $('#clearRoomFormButton').hide();
            $('#toggleRoomFormButton').text('Add Room');
            $('#roomForm')[0].reset();
            $('#submitRoomButton').prop('disabled', true);
        } else {
            $('#roomForm').show();
            $('#clearRoomFormButton').show();
            $('#toggleRoomFormButton').text('Cancel');
        }
        initialFormData = $('#roomForm').serialize();
    });

    // Handle Add/Update Room
    $('#submitRoomButton').click(function (e) {
        e.preventDefault();
        const roomId = $('#room_id').val();
        const actionUrl = roomId
            ? '{{ route("admin.rooms.update", ":id") }}'.replace(':id', roomId)
            : '{{ route("admin.rooms.store") }}';

        $.ajax({
            url: actionUrl,
            method: roomId ? 'PUT' : 'POST',
            data: $('#roomForm').serialize(),
            success: function (response) {
                const room = response.room;
                const newRow = `
                    <tr id="roomRow-${room.id}">
                        <td>${room.room_name}</td>
                        <td>
                            <button class="btn btn-warning btn-sm editRoomButton" data-id="${room.id}">Edit</button>
                            <button class="btn btn-danger btn-sm deleteRoomButton" data-id="${room.id}">Delete</button>
                        </td>
                    </tr>`;
                if (roomId) {
                    $(`#roomRow-${room.id}`).replaceWith(newRow);
                } else {
                    $('#roomTableBody').prepend(newRow);
                }

                $('#roomForm')[0].reset();
                $('#roomForm').hide();
                $('#clearRoomFormButton').hide();
                $('#toggleRoomFormButton').text('Add Room');
                $('#submitRoomButton').text('Add Room').prop('disabled', true);
                initialFormData = $('#roomForm').serialize();

                // Show success modal
                $('#successModal .modal-body').text(roomId ? 'Room updated successfully!' : 'Room added successfully!');
                $('#successModal').modal('show');
            },
            error: function () {
                alert('An error occurred while saving the room.');
            },
        });
    });

    // Handle Edit Room
    $(document).on('click', '.editRoomButton', function () {
        const roomId = $(this).data('id');
        $.get(`{{ route('admin.rooms.index') }}/${roomId}`, function (response) {
            const room = response.room;
            $('#room_id').val(room.id);
            $('#room_name').val(room.room_name);
            $('#submitRoomButton').text('Update Room');
            $('#submitRoomButton').prop('disabled', true);
            $('#roomForm').show();
            $('#clearRoomFormButton').show();
            $('#toggleRoomFormButton').text('Cancel');
            initialFormData = $('#roomForm').serialize();
        });
    });

    // Handle Delete Room
    $(document).on('click', '.deleteRoomButton', function () {
        const roomId = $(this).data('id');

        if (!confirm('Are you sure you want to delete this room?')) {
            return;
        }

        $.ajax({
            url: `{{ route("admin.rooms.index") }}/${roomId}`,
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            },
            success: function (response) {
                if (response.success) {
                    $(`#roomRow-${roomId}`).remove();
                    $('#successModal .modal-body').text('Room deleted successfully!');
                    $('#successModal').modal('show');
                } else {
                    alert('Failed to delete the room.');
                }
            },
            error: function () {
                alert('An error occurred while deleting the room.');
            },
        });
    });

    // Clear form handler
    $('#clearRoomFormButton').click(function () {
        $('#roomForm')[0].reset();
        $('#roomForm').hide();
        $('#clearRoomFormButton').hide();
        $('#toggleRoomFormButton').text('Add Room');
        $('#submitRoomButton').text('Add Room').prop('disabled', true);
        initialFormData = $('#roomForm').serialize();
    });
});
</script>
@endpush
