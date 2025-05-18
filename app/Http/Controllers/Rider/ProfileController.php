<?php

namespace App\Http\Controllers\Rider;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function show()
    {
        if (Auth::check() && Auth::user()->role !== 'rider') {
            abort(403, 'Unauthorized action.');
        }
        $user = Auth::user();
        return view('rider.profile', compact('user'));
    }

    public function edit()
    {
        if (Auth::check() && Auth::user()->role !== 'rider') {
            abort(403, 'Unauthorized action.');
        }
        $user = Auth::user();
        return view('rider.profile-edit', compact('user'));
    }

    public function update(Request $request)
    {
        if (Auth::check() && Auth::user()->role !== 'rider') {
            abort(403, 'Unauthorized action.');
        }
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20|regex:/^[\d\s\-\+\(\)]+$/',
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        if (isset($validated['phone'])) {
            $user->rider()->updateOrCreate(
                ['user_id' => $user->id],
                ['phone_number' => $validated['phone']]
            );
        }

        return redirect()->route('rider.profile')->with('success', 'Profile updated successfully!');
    }

    public function showChangePasswordForm()
    {
        if (Auth::check() && Auth::user()->role !== 'rider') {
            abort(403, 'Unauthorized action.');
        }
        return view('rider.profile-change-password');
    }

    public function changePassword(Request $request)
    {
        if (Auth::check() && Auth::user()->role !== 'rider') {
            abort(403, 'Unauthorized action.');
        }
        $user = Auth::user();

        $validated = $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|confirmed',
        ]);

        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'The current password is incorrect.']);
        }

        $user->update([
            'password' => Hash::make($validated['new_password']),
        ]);

        return redirect()->route('rider.profile.change-password')->with('success', 'Password changed successfully!');
    }
}