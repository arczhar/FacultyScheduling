<?php
// app/Http/Controllers/SubjectController.php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index()
    {
        $subjects = Subject::all();
        return view('admin.subjects.index', compact('subjects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject_id' => 'required|unique:subjects|max:10',
            'subject_code' => 'required|string|max:10',
            'subject_description' => 'required|string|max:255',
            'type' => 'required|in:Lec,Lab',
            'credit_units' => 'required|integer|min:1|max:10',
        ]);

        Subject::create($request->all());

        return redirect()->route('admin.subjects.index')->with('success', 'Subject added successfully.');
    }

    public function edit($id)
    {
        $subject = Subject::findOrFail($id);
        return view('admin.subjects.edit', compact('subject'));
    }

    public function update(Request $request, $id)
    {
        $subject = Subject::findOrFail($id);

        $request->validate([
            'subject_id' => 'required|unique:subjects|max:10',
            'subject_code' => 'required|string|max:10',
            'subject_description' => 'required|string|max:255',
            'type' => 'required|in:Lec,Lab',
            'credit_units' => 'required|integer|min:1|max:10',
        ]);

        $subject->update($request->all());

        return redirect()->route('admin.subjects.index')->with('success', 'Subject updated successfully.');
    }

    public function destroy($id)
    {
        $subject = Subject::findOrFail($id);
        $subject->delete();

        return redirect()->route('admin.subjects.index')->with('success', 'Subject deleted successfully.');
    }
}
