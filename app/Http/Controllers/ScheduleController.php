<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Faculty;
use App\Models\Subject;
use App\Models\Room;
use App\Models\Section;
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
    $schedule = Schedule::with(['subject', 'room', 'section'])->findOrFail($id);

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
            'section_id' => $schedule->section_id,
            'section_name' => $schedule->section->section_name ?? 'N/A',
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
        $sections = Section::all(); // Fetch all sections

        return view('admin.schedules.create', compact('faculties', 'subjects', 'rooms', 'sections'));
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
            'section_id' => 'required|exists:sections,id', // Validate section_id
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'day' => 'required',
        ]);

        // Check for conflicts
        $conflicts = $this->checkForConflicts($request->all());
        if (!empty($conflicts)) {
            return response()->json([
                'success' => false,
                'message' => $conflicts, // Return detailed conflict messages
            ], 422);
        }

        // Create the schedule
        $schedule = Schedule::create($request->all());
        $schedule->load('subject', 'room', 'section'); // Load relationships for response

        return response()->json([
            'success' => true,
            'schedule' => [
                'subject_code' => $schedule->subject->subject_code,
                'subject_description' => $schedule->subject->subject_description,
                'type' => $schedule->subject->type,
                'units' => $schedule->subject->credit_units,
                'day' => $schedule->day,
                'room' => $schedule->room->room_name,
                'section_name' => $schedule->section->section_name ?? 'N/A',
                'start_time' => $schedule->start_time,
                'end_time' => $schedule->end_time,
            ],
            'message' => 'Schedule added successfully!',
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'faculty_id' => 'required|exists:faculty,id',
            'subject_id' => 'required|exists:subjects,id',
            'room_id' => 'required|exists:rooms,id',
            'section_id' => 'required|exists:sections,id', // Validate section_id
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'day' => 'required',
        ]);

        $schedule = Schedule::findOrFail($id);

        // Check for conflicts
        $conflicts = $this->checkForConflicts($request->all(), $id);
        if (!empty($conflicts)) {
            return response()->json([
                'success' => false,
                'message' => $conflicts, // Return detailed conflict messages
            ], 422);
        }

        // Update the schedule
        $schedule->update($request->all());
        $schedule->load('subject', 'room', 'section');

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
                'section_name' => $schedule->section->section_name ?? 'N/A',
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
        $faculty = Faculty::with(['schedules.subject', 'schedules.room', 'schedules.section'])->findOrFail($facultyId);

        $paginatedSchedules = $faculty->schedules->map(function ($schedule) {
            return [
                'id' => $schedule->id,
                'subject_code' => $schedule->subject->subject_code ?? 'N/A',
                'subject_description' => $schedule->subject->subject_description ?? 'N/A',
                'type' => $schedule->subject->type ?? 'N/A',
                'units' => $schedule->subject->credit_units ?? 'N/A',
                'day' => $schedule->day ?? 'N/A',
                'room' => $schedule->room->room_name ?? 'N/A',
                'time' => ($schedule->start_time && $schedule->end_time) 
                    ? $schedule->start_time . ' - ' . $schedule->end_time 
                    : 'N/A',
                'section_name' => $schedule->section->section_name ?? 'N/A', // Correctly fetch the section name
            ];
        });

        return response()->json([
            'position' => $faculty->position,
            'schedules' => $paginatedSchedules->values(),
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
            $conflicts = [];

            // Room Conflict
            $roomConflict = Schedule::where('id', '!=', $excludeId)
                ->where('day', $data['day'])
                ->where('room_id', $data['room_id'])
                ->where(function ($query) use ($data) {
                    $query->whereBetween('start_time', [$data['start_time'], $data['end_time']])
                        ->orWhereBetween('end_time', [$data['start_time'], $data['end_time']])
                        ->orWhere(function ($query) use ($data) {
                            $query->where('start_time', '<=', $data['start_time'])
                                ->where('end_time', '>=', $data['end_time']);
                        });
                })
                ->first();

            if ($roomConflict) {
                $conflicts[] = "Room {$roomConflict->room->room_name} is booked for {$roomConflict->subject->subject_code} ({$roomConflict->subject->subject_description}) from {$roomConflict->start_time} to {$roomConflict->end_time}.";
            }

            // Faculty Conflict
            $facultyConflict = Schedule::where('id', '!=', $excludeId)
                ->where('day', $data['day'])
                ->where('faculty_id', $data['faculty_id'])
                ->where(function ($query) use ($data) {
                    $query->whereBetween('start_time', [$data['start_time'], $data['end_time']])
                        ->orWhereBetween('end_time', [$data['start_time'], $data['end_time']])
                        ->orWhere(function ($query) use ($data) {
                            $query->where('start_time', '<=', $data['start_time'])
                                ->where('end_time', '>=', $data['end_time']);
                        });
                })
                ->first();

            if ($facultyConflict) {
                $conflicts[] = "Faculty {$facultyConflict->faculty->name} is teaching {$facultyConflict->subject->subject_code} ({$facultyConflict->subject->subject_description}) in Room {$facultyConflict->room->room_name} from {$facultyConflict->start_time} to {$facultyConflict->end_time}.";
            }

            // Section Conflict (Optional)
            $sectionConflict = Schedule::where('id', '!=', $excludeId)
                ->where('day', $data['day'])
                ->where('section_id', $data['section_id'])
                ->where(function ($query) use ($data) {
                    $query->whereBetween('start_time', [$data['start_time'], $data['end_time']])
                        ->orWhereBetween('end_time', [$data['start_time'], $data['end_time']])
                        ->orWhere(function ($query) use ($data) {
                            $query->where('start_time', '<=', $data['start_time'])
                                ->where('end_time', '>=', $data['end_time']);
                        });
                })
                ->first();

            if ($sectionConflict) {
                $conflicts[] = "Section {$sectionConflict->section->section_name} is already scheduled for {$sectionConflict->subject->subject_code} ({$sectionConflict->subject->subject_description}) in Room {$sectionConflict->room->room_name} from {$sectionConflict->start_time} to {$sectionConflict->end_time}.";
            }

            return $conflicts;
        }
       
       
     private function checkForConflicts($data, $excludeId = null)
        {
            $messages = [];
        
            // Room Conflict
            $roomConflict = Schedule::where('id', '!=', $excludeId)
                ->where('day', $data['day'])
                ->where('room_id', $data['room_id'])
                ->where(function ($query) use ($data) {
                    $query->whereBetween('start_time', [$data['start_time'], $data['end_time']])
                        ->orWhereBetween('end_time', [$data['start_time'], $data['end_time']])
                        ->orWhere(function ($query) use ($data) {
                            $query->where('start_time', '<=', $data['start_time'])
                                ->where('end_time', '>=', $data['end_time']);
                        });
                })
                ->first();
        
            if ($roomConflict) {
                $messages[] = "Room {$roomConflict->room->room_name} is already booked for {$roomConflict->subject->subject_code} ({$roomConflict->subject->subject_description}) on {$roomConflict->day} from {$roomConflict->start_time} to {$roomConflict->end_time}.";
            }
        
            // Faculty Conflict
            $facultyConflict = Schedule::where('id', '!=', $excludeId)
                ->where('day', $data['day'])
                ->where('faculty_id', $data['faculty_id'])
                ->where(function ($query) use ($data) {
                    $query->whereBetween('start_time', [$data['start_time'], $data['end_time']])
                        ->orWhereBetween('end_time', [$data['start_time'], $data['end_time']])
                        ->orWhere(function ($query) use ($data) {
                            $query->where('start_time', '<=', $data['start_time'])
                                ->where('end_time', '>=', $data['end_time']);
                        });
                })
                ->first();
        
            if ($facultyConflict) {
                $messages[] = "Faculty {$facultyConflict->faculty->name} is already scheduled to teach {$facultyConflict->subject->subject_code} ({$facultyConflict->subject->subject_description}) in Room {$facultyConflict->room->room_name} on {$facultyConflict->day} from {$facultyConflict->start_time} to {$facultyConflict->end_time}.";
            }
        
            // Section Conflict
            $sectionConflict = Schedule::where('id', '!=', $excludeId)
                ->where('day', $data['day'])
                ->where('section_id', $data['section_id'])
                ->where(function ($query) use ($data) {
                    $query->whereBetween('start_time', [$data['start_time'], $data['end_time']])
                        ->orWhereBetween('end_time', [$data['start_time'], $data['end_time']])
                        ->orWhere(function ($query) use ($data) {
                            $query->where('start_time', '<=', $data['start_time'])
                                ->where('end_time', '>=', $data['end_time']);
                        });
                })
                ->first();
        
            if ($sectionConflict) {
                $messages[] = "Section {$sectionConflict->section->section_name} is already scheduled for {$sectionConflict->subject->subject_code} ({$sectionConflict->subject->subject_description}) in Room {$sectionConflict->room->room_name} on {$sectionConflict->day} from {$sectionConflict->start_time} to {$sectionConflict->end_time}.";
            }
        
            return $messages;
    }
        

}
