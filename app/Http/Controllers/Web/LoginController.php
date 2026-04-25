<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard.index');
        }

        return view('auth.login');
    }

    /**
     * Handle authentication attempt.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Audit Log
            \App\Models\AuditLog::create([
                'user_id' => Auth::id(),
                'action' => 'LOGIN',
                'ip_address' => $request->ip(),
                'timestamp' => now()
            ]);

            return redirect()->intended(route('dashboard.index'));
        }

        throw ValidationException::withMessages([
            'email' => __('auth.failed'),
        ]);
    }

    /**
     * Log the user out.
     */
    public function logout(Request $request)
    {
        $userId = Auth::id();
        
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Audit Log
        if ($userId) {
            \App\Models\AuditLog::create([
                'user_id' => $userId,
                'action' => 'LOGOUT',
                'ip_address' => $request->ip(),
                'timestamp' => now()
            ]);
        }

        return redirect('/login');
    }
}
