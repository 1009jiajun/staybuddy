<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Http\Request;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        if (request()->has('redirect')) {
            session(['google_redirect_url' => request()->get('redirect')]);
        }
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback(Request $request)
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $user = User::where('email', $googleUser->getEmail())->first();
            //update user profile image if it exists
            if ($user && !$user->profile_image) {
                $user->profile_image = $googleUser->getAvatar();
                $user->save();
            }

            if (!$user) {
                $user = User::create([
                    'user_id' => Str::uuid(),
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'role' => 'user', // default role
                    'password' => Hash::make(Str::random(12)), // random password
                    'profile_image' => $googleUser->getAvatar(),
                ]);
            }

            Auth::login($user);
            session()->regenerate();

            // Check if the user is an admin
            if ($user->role === 'admin') {
                $redirectUrl = session('google_redirect_url', route('admin.dashboard'));
                session()->forget('google_redirect_url');
            }
            else {
                $redirectUrl = session('google_redirect_url', '/');
                session()->forget('google_redirect_url');
            }

            $request->session()->put('email', $googleUser->getEmail());

            // If a redirect URL is stored, decode it and redirect, otherwise go to home
            return redirect(urldecode($redirectUrl))->with('success', 'Logged in with Google!');
        } catch (\Exception $e) {
            return redirect('/')->with('error', 'Failed to login with Google: ' . $e->getMessage());
        }
    }
}

