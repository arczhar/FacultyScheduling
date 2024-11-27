<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Faculty;
use Illuminate\Support\Facades\Hash;

class AdminFacultyController extends Controller
{
    public function index()
    {
        $faculties = Faculty::paginate(5); // Paginate the faculty list
        return view('admin.faculty.index', compact('faculties'));
    }

    public function show($id)
    {
        try {
            $faculty = Faculty::findOrFail($id); // Find the faculty or throw a 404 error
            return response()->json(['success' => true, 'faculty' => $faculty]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Faculty not found.'], 404);
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_number' => 'required|unique:faculty',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_initial' => 'nullable|string|max:1',
            'position' => 'nullable|string|max:255',
        ]);

        $faculty = Faculty::create($validated);

        return response()->json([
            'success' => true,
            'faculty' => $faculty,
        ]);
    }


    public function update(Request $request, $id)
    {
        $faculty = Faculty::findOrFail($id);

        $validated = $request->validate([
            'id_number' => 'required|unique:faculty,id_number,' . $id,
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_initial' => 'nullable|string|max:1',
            'position' => 'nullable|string|max:255',
        ]);

        $faculty->update($validated);

        return response()->json([
            'success' => true,
            'faculty' => $faculty,
        ]);
    }

    public function edit($id)
    {
        $faculty = Faculty::findOrFail($id); // Fetch faculty by ID
        return response()->json($faculty);
    }

    
    

}
