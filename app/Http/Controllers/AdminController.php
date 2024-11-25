<?php



namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }

        return view('admin.dashboard'); // Admin-specific dashboard view
    }

    public function programChairDashboard()
    {
        if (Auth::user()->role !== 'program chair') {
            abort(403, 'Unauthorized access.');
        }

        return view('programchair.dashboard'); // Program chair-specific dashboard view
    }
}
