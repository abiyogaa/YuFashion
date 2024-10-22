<?php

namespace App\Services;

use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Exception;

class UserService
{
    public function getAllUsers()
    {
        try {
            return User::with('role')->paginate(15);
        } catch (Exception $e) {
            Log::error('Error fetching all users: ' . $e->getMessage());
            throw new Exception('Tidak dapat mengambil data pengguna. Silakan coba lagi nanti.');
        }
    }

    public function getUserById($id)
    {
        try {
            return User::findOrFail($id);
        } catch (Exception $e) {
            Log::error('User not found: ' . $e->getMessage());
            throw new Exception('Pengguna tidak ditemukan.');
        }
    }

    public function createUser(array $data)
    {
        try {
            $data['password'] = Hash::make($data['password']);
            return User::create($data);
        } catch (Exception $e) {
            Log::error('Error creating user: ' . $e->getMessage());
            throw new Exception('Tidak dapat membuat pengguna baru. Silakan coba lagi nanti.');
        }
    }

    public function updateUser($id, array $data)
    {
        try {
            $user = $this->getUserById($id);
            
            if (isset($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            }
            
            if (!$user->update($data)) {
                throw new Exception('Gagal memperbarui data pengguna.');
            }
            
            return $user;
        } catch (Exception $e) {
            Log::error('Error updating user: ' . $e->getMessage());
            throw new Exception('Tidak dapat memperbarui data pengguna. Silakan coba lagi nanti.');
        }
    }

    public function deleteUser($id)
    {
        try {
            $user = $this->getUserById($id);
            if (!$user->delete()) {
                throw new Exception('Gagal menghapus pengguna.');
            }
            return true;
        } catch (Exception $e) {
            Log::error('Error deleting user: ' . $e->getMessage());
            throw new Exception('Tidak dapat menghapus pengguna. Silakan coba lagi nanti.');
        }
    }

    public function getAllRoles()
    {
        try {
            return Role::all();
        } catch (Exception $e) {
            Log::error('Error fetching all roles: ' . $e->getMessage());
            throw new Exception('Tidak dapat mengambil data roles. Silakan coba lagi nanti.');
        }
    }
}