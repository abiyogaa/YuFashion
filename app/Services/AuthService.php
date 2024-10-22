<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Exception;

class AuthService
{
    public function register(array $data)
    {
        try {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'role_id' => $data['role_id'],
            ]);

            return ['status' => true, 'user' => $user];
        } catch (Exception $e) {
            Log::error('Error registering user: ' . $e->getMessage());
            throw new Exception('Tidak dapat mendaftarkan pengguna baru. Silakan coba lagi nanti.');
        }
    }

    public function login(array $credentials, bool $remember = false)
    {
        try {
            if (Auth::attempt($credentials, $remember)) {
                session()->regenerate();
                return ['status' => true, 'user' => Auth::user()];
            }

            return ['status' => false, 'message' => 'Kredensial tidak valid'];
        } catch (Exception $e) {
            Log::error('Error logging in user: ' . $e->getMessage());
            throw new Exception('Tidak dapat melakukan login. Silakan coba lagi nanti.');
        }
    }

    public function logout()
    {
        try {
            Auth::logout();
            session()->invalidate();
            session()->regenerateToken();
        } catch (Exception $e) {
            Log::error('Error logging out user: ' . $e->getMessage());
            throw new Exception('Tidak dapat melakukan logout. Silakan coba lagi nanti.');
        }
    }
}
