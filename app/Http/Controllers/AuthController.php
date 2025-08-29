<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users,email',
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed'
            ],
        ], [
            'email.unique' => 'This email is already registered.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.confirmed' => 'Passwords do not match.',
        ]);

        try {
            $user = User::create([
                'user_id' => Str::uuid()->toString(),
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);
        } catch (\Exception $e) {
            return redirect('/')->with('error', 'Failed to create user: ' . $e->getMessage());
        }

        if (!$user) {
            //add email to the session for error handling
            $request->session()->put('email', $validated['email']);
            return redirect('/')->with('error', 'User registration failed. Please try again.');
        }

        Auth::login($user);

        if (Auth::check()) {
            $request->session()->regenerate();
            return redirect()->intended('/')->with('success', 'Registration successful! You are now logged in.');
        }

        return redirect('/')->with('error', 'Registration succeeded, but login failed. Please log in manually.');
    }


    // public function login(Request $request)
    // {
    //     $credentials = $request->only('email', 'password');

    //     if (Auth::attempt($credentials)) {
    //         $request->session()->regenerate();
    //         return redirect()->intended('/')->with('success', 'Login successful!');
    //     }

    //     return redirect('/')->with('error', 'The provided credentials do not match our records.');

    // }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $request->session()->regenerate();
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            } else {
                return redirect()->intended('/')->with('success', 'Login successful!');
            }
            // add email to the session for error handling
            $request->session()->put('email', $credentials['email']);
        }

        return back()->withErrors([
            'email' => 'Invalid credentials.',
        ]);
    }


    public function logout(Request $request)
    {
        // Clear the 'url.intended' session value
        $request->session()->forget('url.intended');
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
    
    public function admin_logout(Request $request)
    {
        // Clear the 'url.intended' session value
        $request->session()->forget('url.intended');
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

    public function showLoginForm()
    {
        return view('admin.login'); // adjust path if your login view is somewhere else
    }

    
}