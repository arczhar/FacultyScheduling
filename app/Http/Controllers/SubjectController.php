<?php
namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index()
    {
        $subjects = Subject::paginate(10); // Pagination for subject listing
        return view('admin.subjects.index', compact('subjects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject_code' => 'required|string|max:10|unique:subjects,subject_code',
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
            'subject_code' => 'required|string|max:10|unique:subjects,subject_code,' . $id,
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

    public function getSubjectDetails($id)
    {
        $subject = Subject::find($id);

        if (!$subject) {
            return response()->json(['error' => 'Subject not found'], 404);
        }

        return response()->json([
            'subject_description' => $subject->subject_description,
            'type' => $subject->type,
            'units' => $subject->credit_units,
        ]);
    }
}
