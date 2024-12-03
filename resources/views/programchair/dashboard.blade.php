@extends('layouts.admin')

@section('title', 'Program Chair Dashboard')

@section('content')
<div class="container">
    <h1>Program Chair Dashboard</h1>
    <div id="calendar"></div> <!-- Calendar placeholder -->
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.5/index.global.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            events: [
                @foreach($events as $event)
                {
                    title: "{{ $event->title }}",
                    start: "{{ $event->start_date }}",
                    end: "{{ $event->end_date }}",
                    description: "{{ $event->description }}",
                },
                @endforeach
            ],
        });
        calendar.render();
    });
</script>
@endpush
