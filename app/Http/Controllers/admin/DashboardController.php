<?php

namespace App\Http\Controllers\Admin;

use App\Models\Rental;
use App\Models\User;
use App\Models\ClothingItem;
use App\Models\Category;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Exception;

class DashboardController extends Controller
{
    protected $rental;
    protected $user;
    protected $clothes;
    protected $category;

    public function __construct(Rental $rental, User $user, ClothingItem $clothes, Category $category)
    {
        $this->rental = $rental;
        $this->user = $user;
        $this->clothes = $clothes;
        $this->category = $category;
    }

    public function index()
    {
        try {
            $totalRentals = $this->rental->count();
            $totalUsers = $this->user->count();
            $totalClothes = $this->clothes->count();
            $totalCategories = $this->category->count();

            $recentTransactions = $this->rental
                ->with(['user', 'clothingItem'])
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();

            $popularItems = $this->clothes
                ->withCount('rentals as rental_count')
                ->orderBy('rental_count', 'desc')
                ->take(5)
                ->get();

            return view('admin.dashboard', compact(
                'totalRentals',
                'totalUsers', 
                'totalClothes',
                'totalCategories',
                'recentTransactions',
                'popularItems'
            ));
        } catch (Exception $e) {
            Log::error('Error in dashboard index: ' . $e->getMessage());
            return view('admin.error', ['message' => 'An error occurred while loading the dashboard.']);
        }
    }

    public function usersList()
    {
        try {
            $users = $this->user->paginate(15);
            return view('admin.users', ['users' => $users]);
        } catch (Exception $e) {
            Log::error('Error in users list: ' . $e->getMessage());
            return view('admin.error', ['message' => 'An error occurred while loading the users list.']);
        }
    }

    public function clothesList()
    {
        try {
            $clothes = $this->clothes->paginate(15);
            return view('admin.clothes', ['clothes' => $clothes]);
        } catch (Exception $e) {
            Log::error('Error in clothes list: ' . $e->getMessage());
            return view('admin.error', ['message' => 'An error occurred while loading the clothes list.']);
        }
    }

    public function rentalsList()
    {
        try {
            $rentals = $this->rental->with(['user', 'clothes'])->paginate(15);
            return view('admin.rentals', ['rentals' => $rentals]);
        } catch (Exception $e) {
            Log::error('Error in rentals list: ' . $e->getMessage());
            return view('admin.error', ['message' => 'An error occurred while loading the rentals list.']);
        }
    }
}