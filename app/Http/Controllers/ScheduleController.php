<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Faculty;
use App\Models\Subject;
use App\Models\Room;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index()
    {
        $faculties = Faculty::paginate(10);
        return view('admin.schedules.index', compact('faculties'));
    }

    public function show($id)
    {
        $schedule = Schedule::with(['subject', 'room'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'schedule' => [
                'id' => $schedule->id,
                'subject_id' => $schedule->subject_id,
                'subject_code' => $schedule->subject->subject_code,
                'subject_description' => $schedule->subject->subject_description,
                'type' => $schedule->subject->type,
                'units' => $schedule->subject->credit_units,
                'room_id' => $schedule->room_id,
                'room_name' => $schedule->room->room_name,
                'day' => $schedule->day,
                'start_time' => $schedule->start_time,
                'end_time' => $schedule->end_time,
            ],
        ]);
    }



    public function create()
    {
        $faculties = Faculty::all();
        $subjects = Subject::all();
        $rooms = Room::all();
        return view('admin.schedules.create', compact('faculties', 'subjects', 'rooms'));
    }

    public function checkAndSaveSchedule(Request $request)
    {
        $request->validate([
            'faculty_id' => 'required|exists:faculty,id',
            'subject_id' => 'required|exists:subjects,id',
            'room_id' => 'required|exists:rooms,id',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'day' => 'required',
        ]);

        if ($this->hasConflict($request->all())) {
            return response()->json([
                'conflict' => true,
                'message' => 'Schedule conflict detected!',
            ]);
        }

        $schedule = Schedule::create($request->all());
        $schedule->load('subject', 'room');

        return response()->json([
            'conflict' => false,
            'schedule' => [
                'subject_code' => $schedule->subject->subject_code,
                'subject_description' => $schedule->subject->subject_description,
                'type' => $schedule->subject->type,
                'units' => $schedule->subject->credit_units,
                'day' => $schedule->day,
                'room' => $schedule->room->room_name,
                'start_time' => $schedule->start_time,
                'end_time' => $schedule->end_time,
            ],
            'message' => 'Schedule added successfully!',
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'faculty_id' => 'required|exists:faculty,id',
            'subject_id' => 'required|exists:subjects,id',
            'room_id' => 'required|exists:rooms,id',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'day' => 'required',
        ]);

        if ($this->hasConflict($request->all())) {
            return response()->json([
                'conflict' => true,
                'message' => 'Schedule conflict detected!',
            ]);
        }

        $schedule = Schedule::create($request->all());
        $schedule->load('subject', 'room');

        return response()->json([
            'conflict' => false,
            'schedule' => [
                'subject_code' => $schedule->subject->subject_code,
                'subject_description' => $schedule->subject->subject_description,
                'type' => $schedule->subject->type,
                'units' => $schedule->subject->credit_units,
                'day' => $schedule->day,
                'room' => $schedule->room->room_name,
                'start_time' => $schedule->start_time,
                'end_time' => $schedule->end_time,
            ],
            'message' => 'Schedule added successfully!',
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'faculty_id' => 'required|exists:faculties,id',
            'subject_id' => 'required|exists:subjects,id',
            'room_id' => 'required|exists:rooms,id',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'day' => 'required',
        ]);
    
        $schedule = Schedule::findOrFail($id);
    
        // Check for conflicts (optional, based on your logic)
    
        $schedule->update($request->all());
    
        return response()->json([
            'success' => true,
            'message' => 'Schedule updated successfully!',
            'schedule' => [
                'id' => $schedule->id,
                'subject_code' => $schedule->subject->subject_code,
                'subject_description' => $schedule->subject->subject_description,
                'type' => $schedule->subject->type,
                'units' => $schedule->subject->credit_units,
                'day' => $schedule->day,
                'room' => $schedule->room->room_name,
                'start_time' => $schedule->start_time,
                'end_time' => $schedule->end_time,
            ],
        ]);
    }
    

    public function destroy($id)
    {
        $schedule = Schedule::findOrFail($id);
        $schedule->delete();

        return response()->json([
            'success' => true,
            'message' => 'Schedule deleted successfully!'
        ]);
    }

    public function getFacultyDetails(Request $request, $facultyId)
    {
        $page = $request->input('page', 1);
        $perPage = 5;

        $faculty = Faculty::with(['schedules.subject', 'schedules.room'])->findOrFail($facultyId);

        $paginatedSchedules = $faculty->schedules->map(function ($schedule) {
            return [
                'id' => $schedule->id, // Add this line to include the schedule ID
                'subject_code' => $schedule->subject->subject_code ?? 'N/A',
                'subject_description' => $schedule->subject->subject_description ?? 'N/A',
                'type' => $schedule->subject->type ?? 'N/A',
                'units' => $schedule->subject->credit_units ?? 'N/A',
                'day' => $schedule->day ?? 'N/A',
                'room' => $schedule->room->room_name ?? 'N/A',
                'start_time' => $schedule->start_time ?? 'N/A',
                'end_time' => $schedule->end_time ?? 'N/A',
            ];
        })->forPage($page, $perPage);

        return response()->json([
            'position' => $faculty->position,
            'schedules' => $paginatedSchedules->values(),
            'pagination' => [
                'total' => $faculty->schedules->count(),
                'per_page' => $perPage,
                'current_page' => $page,
                'last_page' => ceil($faculty->schedules->count() / $perPage),
            ],
        ]);
    }

    public function getSubjects()
    {
        $subjects = Subject::select('id', 'subject_code', 'subject_description', 'type', 'credit_units')->get();
        return response()->json($subjects);
    }

    public function edit($id)
    {
        $schedule = Schedule::findOrFail($id);
        $faculties = Faculty::all();
        $subjects = Subject::all();
        $rooms = Room::all();
        return view('admin.schedules.edit', compact('schedule', 'faculties', 'subjects', 'rooms'));
    }

    public function viewFacultySchedules($id)
    {
        $faculty = Faculty::with('schedules.subject', 'schedules.room')->findOrFail($id);
        $schedules = $faculty->schedules;

        return view('admin.schedules.view', compact('faculty', 'schedules'));
    }

    private function hasConflict($data, $excludeId = null)
    {
        return Schedule::where('id', '!=', $excludeId)
            ->where('day', $data['day'])
            ->where(function ($query) use ($data) {
                $query->where('room_id', $data['room_id'])
                      ->orWhere('faculty_id', $data['faculty_id']);
            })
            ->where(function ($query) use ($data) {
                $query->whereBetween('start_time', [$data['start_time'], $data['end_time']])
                      ->orWhereBetween('end_time', [$data['start_time'], $data['end_time']])
                      ->orWhere(function ($query) use ($data) {
                          $query->where('start_time', '<=', $data['start_time'])
                                ->where('end_time', '>=', $data['end_time']);
                      });
            })
            ->exists();
    }
}
