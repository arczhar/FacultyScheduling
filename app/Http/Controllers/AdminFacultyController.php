<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Faculty;
use Illuminate\Support\Facades\Hash;

class AdminFacultyController extends Controller
{
    public function index()
    {
        $faculties = Faculty::all(); // Get all faculty members
        return view('admin.faculty.index', compact('faculties'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_number' => 'required|unique:faculty,id_number',
            'first_name' => 'required|string|max:255',
            'middle_initial' => 'nullable|string|max:1',
            'last_name' => 'required|string|max:255',
            'position' => 'nullable|string|max:255',
        ]);

        Faculty::create([
            'id_number' => $request->id_number,
            'first_name' => $request->first_name,
            'middle_initial' => $request->middle_initial,
            'last_name' => $request->last_name,
            'position' => $request->position,
            'password' => Hash::make('faculty2024'), // Set default password
            'name' => 'required|string|max:255', // Add this if 'name' is mandatory
        ]);

        return redirect()->route('admin.faculty.index')->with('success', 'Faculty added successfully.');

    }

    public function update(Request $request, Faculty $faculty)
    {
        $request->validate([
            'id_number' => 'required|unique:faculty,id_number,' . $faculty->id,
            'first_name' => 'required|string|max:255',
            'middle_initial' => 'nullable|string|max:1',
            'last_name' => 'required|string|max:255',
            'position' => 'nullable|string|max:255',
        ]);

        $faculty->update([
            'id_number' => $request->id_number,
            'first_name' => $request->first_name,
            'middle_initial' => $request->middle_initial,
            'last_name' => $request->last_name,
            'position' => $request->position,
        ]);

        return redirect()->route('admin.faculty.index')->with('success', 'Faculty updated successfully.');
    }
}
