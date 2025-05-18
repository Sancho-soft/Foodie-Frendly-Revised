<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RiderController extends Controller
{
    public function index(Request $request)
    {
        $query = Rider::query();

        if ($request->has('search') && $request->input('search')) {
            $searchTerm = $request->input('search');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('email', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('phone', 'LIKE', "%{$searchTerm}%");
            });
        }

        if ($request->has('status_filter') && in_array($request->status_filter, ['active', 'inactive'])) {
            $query->where('status', $request->status_filter);
        }

        $riders = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.rider_user', compact('riders'));
    }

    public function create()
    {
        return view('admin.riders.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:riders,email',
            'password' => 'required|confirmed',
            'phone' => 'required|string|max:20',
            'license_code' => 'required|string|max:50',
            'status' => 'required|in:active,inactive',
        ]);

        try {
            Rider::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'phone' => $validated['phone'],
                'license_code' => $validated['license_code'],
                'status' => $validated['status'],
            ]);

            return redirect()->route('admin.riders.index')
                           ->with('success', 'Rider created successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error creating rider: ' . $e->getMessage())
                        ->withInput();
        }
    }

    public function edit(Rider $rider)
    {
        return view('admin.riders.edit', compact('rider'));
    }

    public function update(Request $request, Rider $rider)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:riders,email,' . $rider->id,
            'phone' => 'required|string|max:20',
            'license_code' => 'required|string|max:50',
            'status' => 'required|in:active,inactive',
        ]);

        try {
            $rider->update($validated);

            return redirect()->route('admin.riders.index')
                           ->with('success', 'Rider updated successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error updating rider: ' . $e->getMessage())
                        ->withInput();
        }
    }

    public function destroy(Rider $rider)
    {
        try {
            $rider->delete();
            return redirect()->route('admin.riders.index')
                           ->with('success', 'Rider deleted successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete rider: ' . $e->getMessage());
        }
    }
}