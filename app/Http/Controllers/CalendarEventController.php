<?php

namespace App\Http\Controllers;

use App\Models\CalendarEvent;
use Illuminate\Http\Request;

class CalendarEventController extends Controller
{
   // Display calendar events (Admin only)
   public function index()
   {
       $events = CalendarEvent::paginate(10); // Pagination for events
       return view('admin.Calendar.index', compact('events'));
   }

   // Dashboard view for Program Chair
   public function programChairDashboard()
    {
        $events = CalendarEvent::all();
        logger($events); // Log the events to confirm they are being fetched
        return view('programchair.dashboard', compact('events'));
    }



    // Show the form to create a new calendar event (Admin only)
    public function create()
    {
        return view('admin.Calendar.create');
    }

    // Store a new calendar event (Admin only)
    // Store a new calendar event (Admin only)
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'description' => 'nullable|string',
        ]);

        $event = CalendarEvent::create($request->all());

        return response()->json(['success' => true, 'event' => $event], 200);
    }


    // Show the form to edit an event (Admin only)
    public function edit(CalendarEvent $calendarEvent)
    {
        return response()->json(['event' => $calendarEvent], 200);
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
 
         return response()->json(['success' => true, 'event' => $calendarEvent], 200);
     }

    // Delete a calendar event (Admin only)
    public function destroy(CalendarEvent $calendarEvent)
    {
        
        $calendarEvent->delete();

        return response()->json(['success' => true], 200);
    }


    
    public function fetchEvents()
    {
        $events = CalendarEvent::all()->map(function ($event) {
            return [
                'title' => $event->title,
                'start' => $event->start_date,
                'end' => $event->end_date,
                'description' => $event->description,
            ];
        });

        return response()->json($events);
    }
}


