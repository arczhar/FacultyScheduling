<?php

namespace App\Http\Controllers;

use App\Models\ExamSchedule;
use App\Models\Room; // Change from ExamRoom to Room
use App\Models\Subject;
use Illuminate\Http\Request;

class ExamScheduleController extends Controller
{
    public function index()
    {
        $rooms = Room::all();
        $timeSlots = [
            '7:00 - 8:30',
            '8:30 - 10:00',
            '10:00 - 11:30',
            '1:00 - 2:30',
            '2:30 - 4:00',
            '4:00 - 5:30',
        ];
        $subjects = Subject::all();
    
        // Fetch existing schedules
        $schedules = ExamSchedule::all();
    
        return view('admin.examrm.examroom', compact('rooms', 'timeSlots', 'subjects', 'schedules'));
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

    public function updateSchedule(Request $request)
{
    $validated = $request->validate([
        'subjectId' => 'required|exists:subjects,id',
        'roomId' => 'required|exists:exam_rooms,id',
        'timeSlot' => 'required|string',
    ]);

    ExamSchedule::updateOrCreate(
        ['room_id' => $validated['roomId'], 'time_slot' => $validated['timeSlot']],
        ['subject_id' => $validated['subjectId']]
    );

    return response()->json(['success' => true, 'message' => 'Schedule updated successfully.']);
}


    public function assignSchedule(Request $request)
    {
        $validated = $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'time_slot' => 'required|string',
            'subject_id' => 'required|exists:subjects,id',
        ]);

        // Save the assignment
        ExamSchedule::updateOrCreate(
            ['room_id' => $validated['room_id'], 'time_slot' => $validated['time_slot']],
            ['subject_id' => $validated['subject_id']]
        );

        return response()->json(['success' => true, 'message' => 'Schedule assigned successfully.']);
    }




    
}
