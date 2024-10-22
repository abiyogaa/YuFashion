<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\AuthService;
use Illuminate\Http\Request;

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
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $remember = $request->has('remember');

        $result = $this->authService->login($credentials, $remember);

        if (!$result['status']) {
            return redirect()->back()->withErrors(['email' => $result['message']])->withInput();
        }

        return redirect()->intended('/dashboard')->with('success', 'Logged in successfully!');
    }

    public function logout()
    {
        $this->authService->logout();
        return redirect()->route('login')->with('success', 'Logged out successfully!');
    }
}
