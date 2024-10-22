<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Exception;

class LoginController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        try {
            $credentials = $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            $remember = $request->has('remember');

            $result = $this->authService->login($credentials, $remember);

            if (!$result['status']) {
                Log::info('Failed login attempt', ['email' => $credentials['email']]);
                return redirect()->back()->withErrors(['email' => $result['message']])->withInput();
            }

            if (Auth::user()->role->name === 'admin') {
                Log::info('Admin logged in', ['user_id' => Auth::id()]);
                return redirect()->route('admin.dashboard')->with('success', 'Logged in successfully!');
            }

            Log::info('User logged in', ['user_id' => Auth::id()]);
            return redirect()->intended('/dashboard')->with('success', 'Logged in successfully!');
        } catch (Exception $e) {
            Log::error('Login error', ['error' => $e->getMessage()]);
            return redirect()->back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function logout()
    {
        try {
            $user_id = Auth::id();
            $this->authService->logout();
            Log::info('User logged out', ['user_id' => $user_id]);
            return redirect()->route('login')->with('success', 'Logged out successfully!');
        } catch (Exception $e) {
            Log::error('Logout error', ['error' => $e->getMessage()]);
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
