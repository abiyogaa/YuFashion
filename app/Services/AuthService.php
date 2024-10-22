<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthService
{
    public function register(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role_id' => $data['role_id'],
        ]);

        return ['status' => true, 'user' => $user];
    }

    public function login(array $credentials, bool $remember = false)
    {
        if (Auth::attempt($credentials, $remember)) {
            session()->regenerate();
            return ['status' => true, 'user' => Auth::user()];
        }

        return ['status' => false, 'message' => 'Invalid credentials'];
    }

    // Handle logout
    public function logout()
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
    }
}
