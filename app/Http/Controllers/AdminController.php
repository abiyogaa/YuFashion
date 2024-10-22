<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rental;
use App\Models\User;
use App\Models\ClothingItem;

class AdminController extends Controller
{
    protected $rental;
    protected $user;
    protected $clothes;

    public function __construct(Rental $rental, User $user, ClothingItem $clothes)
    {
        $this->rental = $rental;
        $this->user = $user;
        $this->clothes = $clothes;
    }

    public function index()
    {
        $totalRentals = $this->rental->count();
        $totalUsers = $this->user->count();
        $totalClothes = $this->clothes->count();

        return view('admin.dashboard', [
            'totalRentals' => $totalRentals,
            'totalUsers' => $totalUsers,
            'totalClothes' => $totalClothes,
        ]);
    }

    public function usersList()
    {
        $users = $this->user->paginate(15);
        return view('admin.users', ['users' => $users]);
    }

    public function clothesList()
    {
        $clothes = $this->clothes->paginate(15);
        return view('admin.clothes', ['clothes' => $clothes]);
    }

    public function rentalsList()
    {
        $rentals = $this->rental->with(['user', 'clothes'])->paginate(15);
        return view('admin.rentals', ['rentals' => $rentals]);
    }
}