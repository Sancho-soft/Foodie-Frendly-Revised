<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Show login page
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Handle login authentication
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if ($user) {
            if (Hash::check($credentials['password'], $user->password)) {
                Auth::login($user);
                $request->session()->regenerate();

                $redirectRoute = match ($user->role) {
                    'admin' => 'admin.dashboard',
                    'customer' => 'home',
                    'rider' => 'rider.index',
                    default => 'cart', // Default to cart for undefined roles
                };

                return redirect()->intended(route($redirectRoute));
            } else {
                return back()->withErrors(['password' => 'The password is incorrect.'])->onlyInput('email');
            }
        } else {
            return back()->withErrors(['email' => 'The email address is not registered.'])->onlyInput('email');
        }
    }

    /**
     * Show registration page
     */
    public function showRegister()
    {
        return view('auth.register');
    }

    /**
     * Handle user registration
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:15|unique:users,phone',
            'password' => 'required|confirmed',
            'role' => 'required|in:admin,customer,rider',
        ], [
            'password.confirmed' => 'The passwords do not match.',
            'phone.unique' => 'The phone number is already registered.',
        ]);

        try {
            User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'password' => bcrypt($validated['password']),
                'role' => $validated['role'],
            ]);

            return redirect()->route('login')
                             ->with('success', 'Registration successful! Please log in.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error creating user: ' . $e->getMessage())
                         ->withInput();
        }
    }

    /**
     * Handle logout
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}