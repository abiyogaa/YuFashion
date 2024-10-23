<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use App\Services\RentalService;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Log;
use Dompdf\Dompdf;

class ManageUserController extends Controller
{
    protected $userService;
    protected $rentalService;

    public function __construct(UserService $userService, RentalService $rentalService)
    {
        $this->userService = $userService;
        $this->rentalService = $rentalService;
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

    public function printRentalHistory($id)
    {
        try {
            $user = $this->userService->getUserById($id);
            $activeRentals = $this->rentalService->getActiveRentalsForUser($id);
            $historyRentals = $this->rentalService->getHistoryRentalsForUser($id);
            
            $rentalHistory = $activeRentals->concat($historyRentals);

            $dompdf = new Dompdf();
            $html = view('admin.user.rental-history-pdf', [
                'user' => $user,
                'rentalHistory' => $rentalHistory
            ])->render();

            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            return $dompdf->stream('rental-history-' . $user->name . '.pdf');
        } catch (Exception $e) {
            Log::error('Error in ManageUserController@printRentalHistory: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while generating rental history.');
        }
    }
}