<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class FacultyController extends Controller
{
    public function dashboard()
    {
        return view('faculty.dashboard'); // Faculty dashboard view
    }

    public function viewSchedule()
    {
        $faculty = auth()->user(); // Get the logged-in faculty user
        $schedules = \App\Models\Schedule::with(['subject', 'room'])
            ->where('faculty_id', $faculty->id)
            ->orderBy('day')
            ->orderBy('start_time')
            ->get();

        return view('faculty.schedule', compact('faculty', 'schedules'));
    }
    public function updateProfile(Request $request)
    {
        $faculty = Auth::guard('faculty')->user();

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'position' => 'nullable|string|max:255',
            'password' => 'nullable|string|confirmed|min:8',
        ]);

        $faculty->first_name = $request->first_name;
        $faculty->last_name = $request->last_name;
        $faculty->position = $request->position;

        if ($request->filled('password')) {
            $faculty->password = Hash::make($request->password);
        }

        $faculty->save();

        return redirect()->route('faculty.profile')->with('success', 'Profile updated successfully.');
    }

    public function viewProfile()
    {
        $faculty = auth()->user();
        return view('faculty.profile', compact('faculty'));
    }

    public function showChangePasswordForm()
    {
        return view('faculty.change-password');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'password' => 'required|min:8|confirmed',
        ]);

        $faculty = Auth::user();
        $faculty->password = Hash::make($request->password);
        $faculty->password_changed = true; // Update password_changed field
        $faculty->save();

        return redirect()->route('faculty.dashboard')->with('success', 'Password changed successfully.');
    }
}
