<?php

namespace App\Http\Controllers;

use App\Models\CalendarEvent;
use Illuminate\Http\Request;

class CalendarEventController extends Controller
{
    // Display calendar events (Admin and Program Chair)
    public function index()
{
    // Use paginate instead of all()
    $events = CalendarEvent::paginate(10); // Adjust the number 10 as needed
    return view('admin.Calendar.index', compact('events'));
}


    public function programChairDashboard()
    {
        $events = CalendarEvent::all(); // Fetch all events
        return view('programchair.dashboard', compact('events')); // Pass events to the view
    }




    // Show the form to create a new calendar event (Admin only)
    public function create()
    {
        return view('admin.Calendar.create');
    }

    // Store a new calendar event (Admin only)
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'description' => 'nullable|string',
        ]);

        CalendarEvent::create($request->all());

        return redirect()->route('admin.calendar-events.index')->with('success', 'Event added successfully.');
    }

    // Show the form to edit an event (Admin only)
    public function edit(CalendarEvent $calendarEvent)
    {
        return view('admin.Calendar.edit', compact('calendarEvent'));
    }

    // Update a calendar event (Admin only)
    public function update(Request $request, CalendarEvent $calendarEvent)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'description' => 'nullable|string',
        ]);

        $calendarEvent->update($request->all());

        return redirect()->route('admin.calendar-events.index')->with('success', 'Event updated successfully.');
    }

    // Delete a calendar event (Admin only)
    public function destroy(CalendarEvent $calendarEvent)
    {
        $calendarEvent->delete();

        return redirect()->route('admin.calendar-events.index')->with('success', 'Event deleted successfully.');
    }

    public function fetchEvents()
{
    $events = CalendarEvent::all()->map(function ($event) {
        return [
            'title' => $event->title,
            'start' => $event->start_date,
            'end' => $event->end_date,
        ];
    });

    return response()->json($events);
}

}
