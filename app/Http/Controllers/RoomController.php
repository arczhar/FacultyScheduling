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
        $rooms = Room::latest()->paginate(10); // Paginate the rooms, 10 per page
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

        $room = Room::create([
            'room_name' => $request->room_name,
        ]);

        return response()->json([
            'success' => true,
            'room' => $room,
        ]);
    }

    /**
     * Show the specified room (for editing).
     */
    public function show(Room $room)
    {
        return response()->json([
            'success' => true,
            'room' => $room,
        ]);
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

        return response()->json([
            'success' => true,
            'room' => $room,
        ]);
    }

    /**
     * Remove the specified room from storage.
     */
    public function destroy(Room $room)
    {
        $room->delete();

        return response()->json([
            'success' => true,
            'message' => 'Room deleted successfully.',
        ]);
    }
}
