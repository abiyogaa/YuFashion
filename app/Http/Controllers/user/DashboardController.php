<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ClothingItem;

class DashboardController extends Controller
{
    public function index()
    {
        $clothingItems = ClothingItem::with(['categories', 'images'])->get();
        
        return view('user.dashboard', compact('clothingItems'));
    }

}
