<?php

namespace App\Http\Controllers;

use App\Models\ExamSchedule;

class ExamScheduleController extends Controller
{
    public function index()
    {
        return view('Admin.examrm.examroom');
    }

    public function fetchEvents()
    {
        $events = ExamSchedule::all()->map(function ($event) {
            return [
                'id' => $event->id,
                'title' => $event->room_name,
                'start' => $event->exam_date . 'T' . $event->start_time,
                'end' => $event->exam_date . 'T' . $event->end_time,
                'details' => $event->details,
            ];
        });

        return response()->json($events);
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'room_name' => 'required|string',
                'exam_date' => 'required|date',
                'start_time' => 'required',
                'end_time' => 'required',
                'details' => 'nullable|string',
            ]);

            ExamSchedule::create($validated);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }


    
    public function destroy($id)
    {
        ExamSchedule::findOrFail($id)->delete();

        return response()->json(['success' => true]);
    }
}
