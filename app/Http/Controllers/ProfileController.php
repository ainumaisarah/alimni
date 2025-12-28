<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    // Show profile page
    public function show()
    {
        return view('profile.show');
    }

    // Update name only
    public function update(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        Auth::user()->update([
            'name' => $request->name,
        ]);

        return back()->with('success', 'Profile updated successfully.');
    }

    // Update profile photo
    public function updateProfilePhoto(Request $request)
    {
        $request->validate([
            'profile_photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $fileName = time() . '.' . $request->file('profile_photo')->getClientOriginalExtension();
        $request->file('profile_photo')->move(public_path('images'), $fileName);

        $user = Auth::user();
        $user->profile_photo_path = 'images/' . $fileName;
        $user->save();

        return back()->with('success', 'Profile photo updated successfully.');
    }

    public function removeProfilePhoto()
    {
        $user = auth()->user();
        if ($user->profile_photo_path && file_exists(public_path($user->profile_photo_path))) {
            unlink(public_path($user->profile_photo_path));
        }
        $user->profile_photo_path = null;
        $user->save();

        return back()->with('success', 'Profile photo removed successfully.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required'],
            'new_password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return back()->with('success', 'Password updated successfully.');
    }

}
