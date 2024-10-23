<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ClothingItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        try {
            $search = $request->input('search');

            $clothingItems = ClothingItem::with(['categories', 'images'])
                ->when($search, function ($query, $search) {
                    return $query->where('name', 'like', "%{$search}%")
                                ->orWhereHas('categories', function ($query) use ($search) {
                                    $query->where('name', 'like', "%{$search}%");
                                });
                })
                ->get();
            
            return view('user.dashboard', compact('clothingItems'));
        } catch (Exception $e) {
            Log::error('Error in DashboardController@index: ' . $e->getMessage());
            return back()->with('error', 'Tidak dapat mengambil data kostum. Silakan coba lagi nanti.');
        }
    }

}