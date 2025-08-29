<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login'); // Redirect unauthenticated users to login
        }

        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access.'); // Authenticated but not admin
        }

        return $next($request);
    }
}
