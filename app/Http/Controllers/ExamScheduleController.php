<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;

class ExamScheduleController extends Controller
{
    public function index()
    {
        $rooms = Room::all(); // Fetch all exam rooms
        $timeSlots = [
            '7:00  - 8:00',
            '8:00  - 09:00',
            '09:00 - 10:00',
            '10:00 - 11:00',
            '11:00 - 12:00',
            '12:00 - 13:00',
            '13:00 - 14:00',
            '14:00 - 15:00',
            '15:00 - 16:00',
            '16:00 - 17:00',
            '17:00 - 18:00',
        ]; // Define the fixed time slots
    
        return view('admin.examroom.index', compact('rooms', 'timeSlots')); // Pass $rooms and $timeSlots to the view
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'room_name' => 'required|string|max:255|unique:exam_rooms',
            'capacity' => 'required|integer|min:1',
        ]);

        $examRoom = ExamRoom::create($request->all());

        return response()->json([
            'success' => true, 
            'examRoom' => $examRoom, 
            'message' => 'Exam room added successfully!'
        ], 200);
    }

    public function edit(ExamRoom $examRoom)
    {
        return response()->json(['examRoom' => $examRoom], 200);
    }

    public function update(Request $request, ExamRoom $examRoom)
    {
        $request->validate([
            'room_name' => 'required|string|max:255|unique:exam_rooms,room_name,' . $examRoom->id,
            'capacity' => 'required|integer|min:1',
        ]);

        $examRoom->update($request->all());

        return response()->json([
            'success' => true, 
            'examRoom' => $examRoom, 
            'message' => 'Exam room updated successfully!'
        ], 200);
    }

    public function destroy(ExamRoom $examRoom)
    {
        $examRoom->delete();

        return response()->json([
            'success' => true, 
            'message' => 'Exam room deleted successfully!'
        ], 200);
    }

    public function examRoomSchedule()
    {
        $rooms = ExamRoom::all(); // Fetch all exam rooms
        $timeSlots = [
            '7:00  - 8:00',
            '8:00  - 09:00',
            '09:00 - 10:00',
            '10:00 - 11:00',
            '11:00 - 12:00',
            '12:00 - 13:00',
            '13:00 - 14:00',
            '14:00 - 15:00',
            '15:00 - 16:00',
            '16:00 - 17:00',
            '17:00 - 18:00',
        ];

        return view('admin.examroom.index', compact('rooms', 'timeSlots')); // Pass both $rooms and $timeSlots
    }
}
