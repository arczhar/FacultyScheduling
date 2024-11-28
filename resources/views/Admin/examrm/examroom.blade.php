@extends('layouts.admin')

@section('title', 'Exam Room Scheduling')

@section('content')
<div class="container mt-4">
    <h1>Exam Room Scheduling</h1>
    <div id="calendar"></div>
</div>

@push('scripts')
<script>
   $(document).ready(function () {
        let calendarEl = document.getElementById('calendar');
        let calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            editable: true,
            selectable: true,
            events: "{{ route('admin.examrm.events') }}", // Fetch events from the backend
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            select: function (info) {
                let title = prompt('Enter Event Title:');
                if (title) {
                    $.ajax({
                        url: "{{ route('admin.examrm.store') }}",
                        method: "POST",
                        data: {
                            title: title,
                            start: info.startStr,
                            end: info.endStr,
                            _token: "{{ csrf_token() }}"
                        },
                        success: function (response) {
                            calendar.refetchEvents();
                            alert('Event added successfully!');
                        }
                    });
                }
                calendar.unselect();
            },
            eventClick: function (info) {
                let title = prompt('Edit Event Title:', info.event.title);
                if (title !== null) {
                    $.ajax({
                        url: "{{ route('admin.examrm.update', '') }}/" + info.event.id,
                        method: "PUT",
                        data: {
                            title: title,
                            start: info.event.start.toISOString(),
                            end: info.event.end ? info.event.end.toISOString() : null,
                            _token: "{{ csrf_token() }}"
                        },
                        success: function (response) {
                            calendar.refetchEvents();
                            alert('Event updated successfully!');
                        }
                    });
                }
            },
            eventDrop: function (info) {
                $.ajax({
                    url: "{{ route('admin.examrm.update', '') }}/" + info.event.id,
                    method: "PUT",
                    data: {
                        title: info.event.title,
                        start: info.event.start.toISOString(),
                        end: info.event.end ? info.event.end.toISOString() : null,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function (response) {
                        calendar.refetchEvents();
                        alert('Event updated successfully!');
                    }
                });
            },
            eventResize: function (info) {
                $.ajax({
                    url: "{{ route('admin.examrm.update', '') }}/" + info.event.id,
                    method: "PUT",
                    data: {
                        title: info.event.title,
                        start: info.event.start.toISOString(),
                        end: info.event.end.toISOString(),
                        _token: "{{ csrf_token() }}"
                    },
                    success: function (response) {
                        calendar.refetchEvents();
                        alert('Event updated successfully!');
                    }
                });
            },
            eventClick: function (info) {
                if (confirm('Are you sure you want to delete this event?')) {
                    $.ajax({
                        url: "{{ route('admin.examrm.destroy', '') }}/" + info.event.id,
                        method: "DELETE",
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        success: function (response) {
                            calendar.refetchEvents();
                            alert('Event deleted successfully!');
                        },
                        error: function (xhr) {
                            alert('Error: ' + xhr.responseJSON.message);
                        }
                    });
                }
            }

        });

        calendar.render();
    });
</script>
@endpush
@endsection
