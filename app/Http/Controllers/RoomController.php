<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    /**
     * Display a listing of the rooms.
     */
    public function index()
    {
        $rooms = Room::all();
        return view('admin.rooms.index', compact('rooms'));
    }

    /**
     * Store a newly created room in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'room_name' => 'required|string|unique:rooms|max:255',
        ]);

        Room::create([
            'room_name' => $request->room_name,
        ]);

        return redirect()->route('admin.rooms.index')->with('success', 'Room added successfully.');
    }

    /**
     * Update the specified room in storage.
     */
    public function update(Request $request, Room $room)
    {
        $request->validate([
            'room_name' => 'required|string|unique:rooms,room_name,' . $room->id . '|max:255',
        ]);

        $room->update([
            'room_name' => $request->room_name,
        ]);

        return redirect()->route('admin.rooms.index')->with('success', 'Room updated successfully.');
    }

    /**
     * Remove the specified room from storage.
     */
    public function destroy(Room $room)
    {
        $room->delete();
        return redirect()->route('admin.rooms.index')->with('success', 'Room deleted successfully.');
    }
}
