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
        $schedules = Schedule::with(['faculty', 'subject', 'room'])->paginate(10);
        return view('admin.schedules.index', compact('schedules'));
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

        // Conflict detection
        $conflict = Schedule::where('day', $request->day)
            ->where(function ($query) use ($request) {
                $query->where('room_id', $request->room_id)
                    ->orWhere('faculty_id', $request->faculty_id);
            })
            ->where(function ($query) use ($request) {
                $query->whereBetween('start_time', [$request->start_time, $request->end_time])
                    ->orWhereBetween('end_time', [$request->start_time, $request->end_time])
                    ->orWhere(function ($query) use ($request) {
                        $query->where('start_time', '<=', $request->start_time)
                                ->where('end_time', '>=', $request->end_time);
                    });
            })
            ->exists();

        if ($conflict) {
            return response()->json([
                'conflict' => true,
                'message' => 'Schedule conflict detected!',
            ]);
        }

        // Save the schedule if no conflict
        $schedule = Schedule::create($request->all());

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
        ]);
    }


    public function getFacultyDetails(Request $request, $facultyId)
    {
        $page = $request->input('page', 1);
        $perPage = 5;

        $faculty = Faculty::with(['schedules.subject', 'schedules.room'])->findOrFail($facultyId);

        $paginatedSchedules = $faculty->schedules->map(function ($schedule) {
            return [
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
    
        $conflict = Schedule::where('day', $request->day)
            ->where(function ($query) use ($request) {
                $query->where('room_id', $request->room_id)
                      ->orWhere('faculty_id', $request->faculty_id);
            })
            ->where(function ($query) use ($request) {
                $query->whereBetween('start_time', [$request->start_time, $request->end_time])
                      ->orWhereBetween('end_time', [$request->start_time, $request->end_time])
                      ->orWhere(function ($query) use ($request) {
                          $query->where('start_time', '<=', $request->start_time)
                                ->where('end_time', '>=', $request->end_time);
                      });
            })
            ->exists();
    
        if ($conflict) {
            return response()->json([
                'conflict' => true,
                'message' => 'Schedule conflict detected!',
            ]);
        }
    
        $schedule = Schedule::create($request->all());
    
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

    public function destroy($id)
    {
        $schedule = Schedule::findOrFail($id);
        $schedule->delete();

        return response()->json([
            'success' => true,
            'message' => 'Schedule deleted successfully!'
        ]);
    }

    public function edit($id)
{
    $schedule = Schedule::findOrFail($id);
    $faculties = Faculty::all();
    $subjects = Subject::all();
    $rooms = Room::all();
    return view('admin.schedules.edit', compact('schedule', 'faculties', 'subjects', 'rooms'));
}

public function update(Request $request, $id)
{
    $schedule = Schedule::findOrFail($id);

    $request->validate([
        'faculty_id' => 'required|exists:faculty,id',
        'subject_id' => 'required|exists:subjects,id',
        'room_id' => 'required|exists:rooms,id',
        'start_time' => 'required|date_format:H:i',
        'end_time' => 'required|date_format:H:i|after:start_time',
        'day' => 'required',
    ]);

    // Conflict detection logic
    $conflict = Schedule::where('id', '!=', $id)
        ->where('day', $request->day)
        ->where(function ($query) use ($request) {
            $query->where('room_id', $request->room_id)
                ->orWhere('faculty_id', $request->faculty_id);
        })
        ->where(function ($query) use ($request) {
            $query->whereBetween('start_time', [$request->start_time, $request->end_time])
                ->orWhereBetween('end_time', [$request->start_time, $request->end_time])
                ->orWhere(function ($query) use ($request) {
                    $query->where('start_time', '<=', $request->start_time)
                        ->where('end_time', '>=', $request->end_time);
                });
        })
        ->exists();

    if ($conflict) {
        return back()->withErrors(['error' => 'Schedule conflict detected!']);
    }

    // Update the schedule
    $schedule->update($request->all());

    return redirect()->route('admin.schedules.index')->with('success', 'Schedule updated successfully.');
}

    
}
