<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Exception;

class ProfileController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function show()
    {
        try {
            $user = Auth::user();
            return view('profile.show', compact('user'));
        } catch (Exception $e) {
            Log::error('Error showing profile: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while loading your profile.');
        }
    }

    public function edit()
    {
        try {
            $user = Auth::user();
            return view('profile.edit', compact('user'));
        } catch (Exception $e) {
            Log::error('Error editing profile: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while loading the edit profile page.');
        }
    }

    public function update(Request $request)
    {
        try {
            $user = Auth::user();
            
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
                'password' => 'nullable|string|min:8|confirmed',
            ]);

            if (empty($validatedData['password'])) {
                unset($validatedData['password']);
            }

            $this->userService->updateUser($user->id, $validatedData);

            return redirect()->route('profile.show')->with('success', 'Profile updated successfully.');
        } catch (Exception $e) {
            Log::error('Error updating profile: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while updating your profile.')->withInput();
        }
    }
}