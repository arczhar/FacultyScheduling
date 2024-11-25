<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Customize credentials for admin (email) and faculty (id_number).
     */
    protected function credentials(Request $request)
    {
        $username = $request->input('username');
        $password = $request->input('password');

        // Check if username is an email for admin
        if (filter_var($username, FILTER_VALIDATE_EMAIL)) {
            return ['email' => $username, 'password' => $password];
        }

        // Otherwise, treat it as faculty ID number
        return ['id_number' => $username, 'password' => $password];
    }

    /**
     * Attempt login on both guards: 'web' for admin and 'faculty' for faculty.
     */
    protected function attemptLogin(Request $request)
    {
        $credentials = $this->credentials($request);

        \Log::info('Attempting login with credentials:', $credentials); // Log the credentials

        if (isset($credentials['email']) && Auth::guard('web')->attempt($credentials, $request->filled('remember'))) {
            \Log::info('Admin login successful');
            return true;
        }

        if (isset($credentials['id_number']) && Auth::guard('faculty')->attempt($credentials, $request->filled('remember'))) {
            \Log::info('Faculty login successful');
            return true;
        }

        \Log::info('Login failed');
        return false;
    }


    
    /**
     * Validate the login request.
     */
    protected function validateLogin(Request $request)
    {
        // Only check that 'username' and 'password' are provided
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);
    }

    /**
     * Redirect after login based on guard.
     */
    protected function authenticated(Request $request, $user)
    {
        // Redirect based on user role
        if (strtolower($user->role) === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif (strtolower($user->role) === 'program chair') {
            return redirect()->route('programchair.dashboard');
        }

        return redirect('/'); // Default fallback
    }




    /**
     * Redirect after logout.
     */
    protected function loggedOut(Request $request)
    {
        return redirect('/login');
    }
}
