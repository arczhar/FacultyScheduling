<?php

namespace App\Http\Controllers;

use App\Models\Section;
use Illuminate\Http\Request;

class AdminSectionController extends Controller
{
    public function index()
    {
        $sections = Section::paginate(5); // Paginate sections
        return view('admin.section.index', compact('sections'));
    }

    public function store(Request $request)
    {
        // Validation
        $validated = $request->validate([
            'section_name' => 'required|unique:sections,section_name', // Only validate section_name
        ]);

        // Create Section
        $section = Section::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Section added successfully!',
            'section' => $section,
        ]);
    }

    public function update(Request $request, Section $section)
    {
        $validated = $request->validate([
            'section_name' => 'required|unique:sections,section_name,' . $section->id,
        ]);
    
        $section->update($validated);
    
        return response()->json([
            'success' => true,
            'message' => 'Section updated successfully!',
            'section' => $section,
        ]);
    }
    

    public function destroy(Section $section)
    {
        // Delete Section
        $section->delete();

        return response()->json([
            'success' => true,
            'message' => 'Section deleted successfully!',
        ]);
    }

    public function show($id)
    {
        try {
            $section = Section::findOrFail($id); // Fetch the section or throw a 404 error
            return response()->json(['success' => true, 'section' => $section]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Section not found.'], 404);
        }
    }
    

}
