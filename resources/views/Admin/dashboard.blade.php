@extends('layouts.admin')

@section('title', 'Dashboard') <!-- Updated Title -->

@section('content')
<div class="container mt-4">
    <h1>Dashboard</h1>
    <p>Welcome to the Dashboard</p>

    <!-- Button to Manage Calendar (Admin Only) -->
    @if(Auth::user()->role === 'admin')
        <a href="{{ route('admin.calendar-events.index') }}" class="btn btn-primary mb-4">Manage Calendar</a>
    @endif

    <!-- FullCalendar Integration -->
    <div id="calendar"></div>
</div>

<!-- Include FullCalendar CSS and JS -->
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.css' rel='stylesheet' />
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js'></script>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            events: '/api/calendar-events', // Adjust the route if necessary
        });
        calendar.render();
    });
</script>
@endpush
@endsection
