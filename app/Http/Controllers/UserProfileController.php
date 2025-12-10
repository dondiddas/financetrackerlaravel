<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserProfileController extends Controller
{
    /**
     * Update authenticated user's profile (name, email, password).
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        if (! $user) {
            return redirect()->back()->with('error', 'You must be logged in to update profile.');
        }

        $data = $request->validate([
            'first_name' => 'nullable|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => ['required','email','max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user->first_name = $data['first_name'] ?? $user->first_name;
        $user->middle_name = $data['middle_name'] ?? $user->middle_name;
        $user->last_name = $data['last_name'] ?? $user->last_name;
        $user->email = $data['email'];

        if (! empty($data['password'])) {
            $user->password = $data['password'];
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
