<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Facades\File;
use App\Models\User;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    public function adminEdit(Request $request): View
    {
        return view('admin.profile', [
            'user' => $request->user(),
        ]);
    }

    public function crewEdit(Request $request): View
    {
        return view('crew.profile', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information and picture.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $validatedData = $request->validated();

        // Update profile information
        $user->fill($validatedData);
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        // Upload profile picture
        if ($request->hasFile('picture')) {
            $request->validate([
                'picture' => ['image', 'mimes:jpg,png,gif', 'max:3072']
            ]);

            $directory = 'public/pictures';
            if (!File::exists($directory)) {
                File::makeDirectory($directory, 0755, true);
            }

            $path = $request->file('picture')->store($directory);
            $user->picture = $path;
        }

        $user->save();

        return redirect()->route('profile.edit')->with('status', 'Profile updated successfully');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'password'] // Assuming you have a custom validation rule for 'password'
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->to('/');
    }

    public function store(Request $request)
    {
        $request->validate([
            'picture' => ['required', 'image', 'mimes:jpg,png,gif', 'max:3072']
        ]);

        $directory = 'public/pictures';
        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $path = $request->file('picture')->store($directory);

        $user = $request->user();
        $user->picture = $path;
        $user->save();

        return redirect()->back()->with('status', 'Picture uploaded successfully');
    }
}
