@extends('layouts.admin')

@section('title', 'Exam Room Scheduling')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Exam Room Scheduling</h1>

    <!-- Exam Room Schedule Table -->
    <div class="table-responsive">
        <table class="table table-bordered text-center">
        <thead style="background-color: maroon; color: white;">
            <tr>
                <th>Time Slots</th>
                @foreach($rooms as $room)
                    <th>{{ $room->room_name }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($timeSlots as $timeSlot)
                <tr>
                    <td>{{ $timeSlot }}</td>
                    @foreach($rooms as $room)
                        <td id="cell-{{ $room->id }}-{{ $timeSlot }}" class="droppable" data-room-id="{{ $room->id }}" data-time-slot="{{ $timeSlot }}">
                            <!-- Drop Area -->
                            <div class="drop-area">
                                @foreach ($schedules as $schedule)
                                    @if ($schedule->room_id == $room->id && $schedule->time_slot == $timeSlot)
                                        <div class="scheduled-subject" style="margin: 5px; padding: 5px; border: 1px solid #ddd;">
                                            {{ $schedule->subject->subject_code }} - {{ $schedule->subject->subject_description }}
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>

        </table>
    </div>
</div>
<div class="container mt-4">
    <h2>Available Subjects</h2>
    <p>Please, Drag and Drop the subject to your desired Time slot and Room</p>
    <div class="row">
        <div class="col-md-12">
            <ul class="list-group">
            @foreach ($subjects as $subject)
                <div class="draggable-subject" draggable="true" data-subject-id="{{ $subject->id }}">
                    {{ $subject->subject_code }} - {{ $subject->subject_description }}
                </div>
            @endforeach
            </ul>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const subjects = document.querySelectorAll('.draggable-subject');
    const dropAreas = document.querySelectorAll('.droppable');

    subjects.forEach(subject => {
        subject.addEventListener('dragstart', (e) => {
            e.dataTransfer.setData('subject-id', subject.dataset.subjectId);
            e.dataTransfer.setData('subject-content', subject.textContent);
        });
    });

    dropAreas.forEach(area => {
        area.addEventListener('dragover', (e) => {
            e.preventDefault();
        });

        area.addEventListener('drop', (e) => {
            e.preventDefault();
            const subjectId = e.dataTransfer.getData('subject-id');
            const subjectContent = e.dataTransfer.getData('subject-content');

            // Add subject to the cell
            const subjectElement = document.createElement('div');
            subjectElement.textContent = subjectContent;
            subjectElement.classList.add('scheduled-subject');
            area.appendChild(subjectElement);

            // Send data to the server
            const roomId = area.dataset.roomId;
            const timeSlot = area.dataset.timeSlot;
            assignScheduleToServer(subjectId, roomId, timeSlot);
        });
    });
});

// Function to send data to the server
function assignScheduleToServer(subjectId, roomId, timeSlot) {
    fetch('/admin/exam-schedule/assign', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ subject_id: subjectId, room_id: roomId, time_slot: timeSlot })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Schedule assigned successfully');
        } else {
            alert('Failed to assign schedule: ' + data.message);
        }
    })
    .catch(error => console.error('Error:', error));
}




function updateScheduleOnServer(subjectId, roomId, timeSlot) {
    fetch('{{ route('examroom.updateSchedule') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ subjectId, roomId, timeSlot })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Schedule updated successfully!');
        } else {
            alert('Failed to update schedule: ' + data.message);
        }
    })
    .catch(error => console.error('Error:', error));
}




</script>
@endpush
