@extends('layouts.admin')

@section('title', 'Dashboard') <!-- Set the page title -->

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <h1 class="text-center mb-4">Dashboard</h1>
            <p class="text-center">Welcome to the Admin Dashboard</p>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <!-- Calendar -->
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title text-center">Schedule Calendar</h5>
                    <div id="calendar"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- FullCalendar CSS and JS -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'timeGridWeek', // Weekly view
            editable: true, // Enable drag-and-drop
            events: '/api/schedules', // Fetch events from API
            eventDrop: function(info) {
                // Update the event time in database on drag-and-drop
                axios.post(`/api/schedules/${info.event.id}/update`, {
                    start: info.event.start.toISOString(),
                    end: info.event.end.toISOString(),
                }).then(response => {
                    alert('Event updated successfully!');
                }).catch(error => {
                    alert('Failed to update event.');
                    console.error(error);
                });
            },
            dateClick: function(info) {
                // Example: Open a modal to create a new event
                alert('Clicked on: ' + info.dateStr);
            }
        });
        calendar.render();
    });
</script>
@endsection
