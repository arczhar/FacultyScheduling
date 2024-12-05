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
        try {
            $validated = $request->validate([
                'id_number' => 'required|unique:faculty,id_number',
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'middle_initial' => 'nullable|string|max:1',
                'position' => 'nullable|string|max:255',
            ]);

            $faculty = Faculty::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Faculty added successfully!',
                'faculty' => $faculty,
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->validator->errors(), // Return all validation errors
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while adding the faculty.',
            ], 500);
        }
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
            'message' => 'Faculty updated successfully!',
            'faculty' => $faculty,
        ]);
    }

    public function destroy($id)
    {
        try {
            $faculty = Faculty::findOrFail($id);
            $faculty->delete();

            return response()->json([
                'success' => true,
                'message' => 'Faculty deleted successfully!',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete faculty. Please try again.',
            ], 500);
        }
    }

    
        

}
