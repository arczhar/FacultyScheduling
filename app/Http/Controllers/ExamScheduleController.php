<?php

namespace App\Http\Controllers;

use App\Models\ExamSchedule;
use App\Models\Room;
use App\Models\Subject;
use App\Models\Section;
use Illuminate\Http\Request;

class ExamScheduleController extends Controller
{
    public function index()
    {
        $rooms = Room::all(); // Fetch all rooms
        $timeSlots = [
            '7:00 - 8:00',
            '8:00 - 9:00',
            '9:00 - 10:00',
            '10:00 - 11:00',
            '11:00 - 12:00',
            '12:00 - 13:00',
            '13:00 - 14:00',
            '14:00 - 15:00',
            '15:00 - 16:00',
            '16:00 - 17:00',
            '17:00 - 18:00',
        ]; // Define time slots
        
        // Fetch existing exam schedules grouped by room and time slot
        $examSchedules = ExamSchedule::with(['room', 'subject', 'section'])
            ->get()
            ->groupBy(['room_id', 'time_slot']);
        
        return view('admin.examroom.index', compact('rooms', 'timeSlots', 'examSchedules'));
    }

  
    public function create()
    {
        $rooms = Room::all();
        $timeSlots = [
            '7:00 - 8:00', '8:00 - 9:00', '9:00 - 10:00',
            '10:00 - 11:00', '11:00 - 12:00', '12:00 - 13:00',
            '13:00 - 14:00', '14:00 - 15:00', '15:00 - 16:00',
            '16:00 - 17:00', '17:00 - 18:00',
        ];
        $subjects = Subject::all();
        $sections = Section::all();

        return view('admin.examroom.create', compact('rooms', 'timeSlots', 'subjects', 'sections'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'section_id' => 'required|exists:sections,id',
            'room_id' => 'required|exists:rooms,id',
            'time_slot' => 'required|string',
            'exam_date' => 'required|date',
        ]);

        // Check for conflicts
        $conflict = ExamSchedule::where('room_id', $request->room_id)
            ->where('time_slot', $request->time_slot)
            ->where('exam_date', $request->exam_date)
            ->exists();

        if ($conflict) {
            return response()->json([
                'success' => false,
                'message' => 'This room is already booked for the selected time slot and date.',
            ], 422);
        }

        $examSchedule = ExamSchedule::create($request->all());

        return response()->json([
            'success' => true,
            'examSchedule' => $examSchedule,
            'message' => 'Exam schedule added successfully!',
        ]);
    }

    public function edit(ExamSchedule $examSchedule)
    {
        return response()->json(['examSchedule' => $examSchedule], 200);
    }

    public function update(Request $request, ExamSchedule $examSchedule)
    {
        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'section_id' => 'required|exists:sections,id',
            'room_id' => 'required|exists:rooms,id',
            'time_slot' => 'required|string',
            'exam_date' => 'required|date',
        ]);

        // Check for conflicts excluding the current schedule
        $conflict = ExamSchedule::where('id', '!=', $examSchedule->id)
            ->where('room_id', $request->room_id)
            ->where('time_slot', $request->time_slot)
            ->where('exam_date', $request->exam_date)
            ->exists();

        if ($conflict) {
            return response()->json([
                'success' => false,
                'message' => 'This room is already booked for the selected time slot and date.',
            ], 422);
        }

        $examSchedule->update($request->all());

        return response()->json([
            'success' => true,
            'examSchedule' => $examSchedule,
            'message' => 'Exam schedule updated successfully!',
        ]);
    }

    public function destroy(ExamSchedule $examSchedule)
    {
        $examSchedule->delete();

        return response()->json([
            'success' => true,
            'message' => 'Exam schedule deleted successfully!',
        ]);
    }
}
