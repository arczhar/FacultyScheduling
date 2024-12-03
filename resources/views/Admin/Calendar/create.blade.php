@extends('layouts.admin')

@section('title', 'Create Event')

@section('content')
<div class="container">
    <h1 class="mb-4">Add New Event</h1>

    <form action="{{ route('admin.calendar-events.store') }}" method="POST">
        @csrf
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

        <button type="submit" class="btn btn-success">Save Event</button>
        <a href="{{ route('admin.calendar-events.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
