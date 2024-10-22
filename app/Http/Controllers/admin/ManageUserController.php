<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Log;

class ManageUserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index()
    {
        try {
            $users = $this->userService->getAllUsers();
            return view('admin.user.index', compact('users'));
        } catch (Exception $e) {
            Log::error('Error in ManageUserController@index: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while fetching users.');
        }
    }

    public function create()
    {
        $roles = $this->userService->getAllRoles();
        return view('admin.user.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
        ]);

        try {
            $this->userService->createUser($validatedData);
            return redirect()->route('users.index')->with('success', 'User created successfully.');
        } catch (Exception $e) {
            Log::error('Error in ManageUserController@store: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while creating the user.')->withInput();
        }
    }

    public function edit($id)
    {
        try {
            $user = $this->userService->getUserById($id);
            $roles = $this->userService->getAllRoles();
            return view('admin.user.edit', compact('user', 'roles'));
        } catch (Exception $e) {
            Log::error('Error in ManageUserController@edit: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while fetching user data.');
        }
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'role_id' => 'required|exists:roles,id',
        ]);

        if ($request->filled('password')) {
            $validatedData['password'] = $request->validate([
                'password' => 'required|string|min:8|confirmed',
            ])['password'];
        }

        try {
            $this->userService->updateUser($id, $validatedData);
            return redirect()->route('users.index')->with('success', 'User updated successfully.');
        } catch (Exception $e) {
            Log::error('Error in ManageUserController@update: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while updating the user.')->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $this->userService->deleteUser($id);
            return redirect()->route('users.index')->with('success', 'User deleted successfully.');
        } catch (Exception $e) {
            Log::error('Error in ManageUserController@destroy: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while deleting the user.');
        }
    }
}