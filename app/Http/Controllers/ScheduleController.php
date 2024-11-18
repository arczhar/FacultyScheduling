<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Faculty;
use App\Models\Subject;
use App\Models\Room;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the schedules.
     */
    public function index()
    {
        $schedules = Schedule::with(['faculty', 'subject', 'room'])->get();
        $faculties = Faculty::all();
        $subjects = Subject::all();
        $rooms = Room::all();

        return view('admin.schedules.index', compact('schedules', 'faculties', 'subjects', 'rooms'));
    }

    /**
     * Store a newly created schedule in storage with conflict detection.
     */
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
            return redirect()->back()->withErrors(['error' => 'Schedule conflict detected!']);
        }

        Schedule::create($request->all());

        return redirect()->route('admin.schedules.index')->with('success', 'Schedule created successfully.');
    }

    /**
     * Remove the specified schedule from storage.
     */
    public function destroy($id)
    {
        $schedule = Schedule::findOrFail($id);
        $schedule->delete();

        return redirect()->route('admin.schedules.index')->with('success', 'Schedule deleted successfully.');
    }
}
