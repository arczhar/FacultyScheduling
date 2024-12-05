@extends('layouts.admin')

@section('title', 'Manage Exam Rooms')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Exam Room Scheduling</h1>

    <!-- Toggle Form Button -->
    <button type="button" id="toggleFormButton" class="btn btn-primary mb-3">Add Exam Room</button>

    <!-- Add/Edit Exam Room Form -->
    <form id="examRoomForm" style="display: none;">
        @csrf
        <input type="hidden" id="room_id" name="room_id">
        <div class="form-group">
            <label for="room_name">Room Name</label>
            <input type="text" class="form-control" id="room_name" name="room_name" required>
        </div>
        <button type="button" id="submitExamRoomButton" class="btn btn-success">Save</button>
        <button type="button" id="cancelButton" class="btn btn-secondary">Cancel</button>
    </form>

    <!-- Exam Room Scheduling Table -->
    <table class="table table-bordered">
    <thead style="background-color: maroon; color: white;">
        <tr>
            <th>Time Slots</th>
            @foreach ($rooms as $room)
                <th>{{ $room->room_name }}</th>
            @endforeach
        </tr>
    </thead>
        <tbody>
            @foreach ($timeSlots as $timeSlot)
                <tr>
                    <td>{{ $timeSlot }}</td>
                    @foreach ($rooms as $room)
                        <td><!-- Placeholder for room scheduling --></td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>


</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteConfirmationModal" tabindex="-1" role="dialog" aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteConfirmationModalLabel">Confirm Delete</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">Are you sure you want to delete this room?</div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" id="confirmDeleteButton" class="btn btn-danger">Delete</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function () {
    // Toggle form visibility
    $('#toggleFormButton').click(function () {
        $('#examRoomForm').toggle();
        $(this).text(function (i, text) {
            return text === "Add Exam Room" ? "Cancel" : "Add Exam Room";
        });
    });

    // Cancel button hides the form
    $('#cancelButton').click(function () {
        $('#examRoomForm').hide();
        $('#toggleFormButton').text('Add Exam Room');
        $('#examRoomForm')[0].reset();
    });

    // Save Exam Room
    $('#submitExamRoomButton').click(function () {
        const roomId = $('#room_id').val();
        const actionUrl = roomId
            ? `{{ route('admin.examroom.update', ':id') }}`.replace(':id', roomId)
            : `{{ route('admin.examroom.store') }}`;
        const method = roomId ? 'PUT' : 'POST';

        $.ajax({
            url: actionUrl,
            method: method,
            data: $('#examRoomForm').serialize(),
            success: function (response) {
                if (roomId) {
                    $(`th[data-id="${roomId}"]`).text(response.room.room_name);
                } else {
                    location.reload(); // Reload to show the new room
                }
                $('#examRoomForm')[0].reset();
                $('#examRoomForm').hide();
                $('#toggleFormButton').text('Add Exam Room');
            },
            error: function () {
                alert('An error occurred while saving the exam room.');
            }
        });
    });

    // Edit Exam Room
    $('.editRoomButton').click(function () {
        const roomId = $(this).data('id');
        const roomName = $(this).data('name');
        $('#room_id').val(roomId);
        $('#room_name').val(roomName);
        $('#examRoomForm').show();
        $('#toggleFormButton').text('Cancel');
    });

    // Delete Exam Room
    let deleteRoomId = null;
    $('.deleteRoomButton').click(function () {
        deleteRoomId = $(this).data('id');
        $('#deleteConfirmationModal').modal('show');
    });

    $('#confirmDeleteButton').click(function () {
        if (deleteRoomId) {
            $.ajax({
                url: `{{ route('admin.examroom.destroy', ':id') }}`.replace(':id', deleteRoomId),
                method: 'DELETE',
                success: function () {
                    location.reload(); // Reload to remove the deleted room
                },
                error: function () {
                    alert('An error occurred while deleting the exam room.');
                }
            });
        }
    });
});
</script>
@endpush
