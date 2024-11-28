<?php
namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function show($id)
    {
        $subject = Subject::find($id);

        if ($subject) {
            return response()->json(['success' => true, 'subject' => $subject]);
        }

        return response()->json(['success' => false, 'message' => 'Subject not found'], 404);
    }


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

        $subject = Subject::create($request->all());

        return response()->json(['success' => true, 'subject' => $subject]);
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

        return response()->json(['success' => true, 'subject' => $subject]);
    }

    public function destroy($id)
    {
        $subject = Subject::find($id);

        if ($subject) {
            $subject->delete();

            return response()->json(['success' => true, 'message' => 'Subject deleted successfully.']);
        }

        return response()->json(['success' => false, 'message' => 'Subject not found.'], 404);
    }


    public function getSubjectDetails($id)
    {
        $subject = Subject::find($id);

        if (!$subject) {
            return response()->json(['error' => 'Subject not found'], 404);
        }

        return response()->json(['success' => true, 'subject' => $subject]);
    }

}
