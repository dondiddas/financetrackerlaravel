<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class UserProfileController extends Controller
{
    /**
     * Update authenticated user's profile (name, email, password, photo).
     */
    public function update(Request $request)
    {

        
        $user = Auth::user();
        if (! $user) {
            return redirect()->back()->with('error', 'You must be logged in to update profile.');
        }


        // Validate input
        $data = $request->validate([
            'first_name'    => 'nullable|string|max:255',
            'middle_name'   => 'nullable|string|max:255',
            'last_name'     => 'nullable|string|max:255',
            'email'         => ['nullable','email','max:255', Rule::unique('users')->ignore($user->id)],
            'password'      => 'nullable|string|min:8|confirmed',
            'profile_photo' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:15000',
        ]);


        // Update basic info
        $user->first_name  = $data['first_name'] ?? $user->first_name;
        $user->middle_name = $data['middle_name'] ?? $user->middle_name;
        $user->last_name   = $data['last_name'] ?? $user->last_name;
        $user->email       = $data['email'] ?? $user->email;

        // Update password if provided
        if (! empty($data['password'])) {
            $user->password = bcrypt($data['password']);
        }

        if ($request->hasFile('profile_photo')) {
            // Delete old photo if exists
            if ($user->profile_photo && Storage::disk('public')->exists($user->profile_photo)) {
                Storage::disk('public')->delete($user->profile_photo);
            }

            $path = $request->file('profile_photo')->store('profile_photos', 'public');
            $user->profile_photo = $path;
        }

        $user->save();

        return redirect()->back()->with('profile_success', 'Profile updated successfully.');
    }

    /**
     * Show settings page where user can toggle preferences like dark mode.
     */
    public function showSettings()
    {
        $user = Auth::user();
        if (! $user) {
            return redirect()->route('login');
        }

        return view('settings', compact('user'));
    }
}
