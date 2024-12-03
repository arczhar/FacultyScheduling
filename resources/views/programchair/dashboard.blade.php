@extends('layouts.admin') {{-- Using the admin layout --}}

@section('title', 'Program Chair Dashboard')

@section('content')
<div class="container mt-4">
    <h1>Program Chair Dashboard</h1>
    <div id="calendar"></div>
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
                },
                @endforeach
            ],
        });

        calendar.render();
    });
</script>
@endpush
