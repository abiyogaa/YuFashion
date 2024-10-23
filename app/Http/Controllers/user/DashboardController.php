<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ClothingItem;
use App\Models\Category;
use App\Models\Rental;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Mengambil semua clothing items beserta gambar dan kategorinya
        $clothingItems = ClothingItem::with(['categories', 'images'])->get();
        
        return view('user.dashboard', compact('clothingItems'));
    }

}
