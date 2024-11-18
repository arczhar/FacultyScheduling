@extends('layouts.admin')

@section('title', 'dashboard') <!-- Change the title appropriately -->

@section('content')
    <h1>Dishboard</h1>
    <p>THIS IS DASHBOARD</p>

<link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.css' rel='stylesheet' />
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js'></script>

<div id="calendar"></div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'timeGridWeek', // Weekly view
        editable: true, // Enable drag-and-drop
        events: '/api/schedules', // Fetch events from API
        eventDrop: function(info) {
            // Function to update the event time in database on drag-and-drop
            axios.post(`/api/schedules/${info.event.id}/update`, {
                start: info.event.start,
                end: info.event.end,
            });
        },
        dateClick: function(info) {
            // Function to open a modal or form to create new schedule
        }
    });
    calendar.render();
});
</script>





@endsection
