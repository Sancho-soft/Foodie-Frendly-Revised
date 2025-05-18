<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $role)
    {
        if (!Auth::check()) {
            return redirect('/login')->with('error', 'Please log in to access this page.');
        }

        $user = Auth::user();

        // If the role doesn't match, redirect to the user's appropriate dashboard
        if ($user->role !== $role) {
            $redirectRoute = match ($user->role) {
                'admin' => 'admin.dashboard',
                'customer' => 'home',
                'rider' => 'rider.index',
            };


            return redirect()->route($redirectRoute)->with('error', 'You are not authorized to access this page.');
        }

        return $next($request);
    }
}