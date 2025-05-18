<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, ...$guards)
    {
        if (Auth::guard()->check()) {
            return redirect()->route('index'); // Redirect to home if already logged in
        }
        return $next($request);
    }
}
