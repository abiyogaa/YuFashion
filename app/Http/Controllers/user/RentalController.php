<?php

namespace App\Http\Controllers\User;

use App\Models\Rental;
use App\Models\ClothingItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RentalController extends Controller
{
    public function create($clothing_item_id)
    {
        $item = ClothingItem::findOrFail($clothing_item_id);
        return view('user.rent', compact('item'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'rental_date' => 'required|date',
            'return_date' => 'required|date|after:rental_date',
            'total_price' => 'required|integer',
        ]);

        Rental::create([
            'user_id' => auth()->user()->id,
            'clothing_item_id' => $request->clothing_item_id,
            'rental_date' => $request->rental_date,
            'return_date' => $request->return_date,
            'total_price' => $request->total_price,
            'status' => 'pending',
        ]);

        return redirect()->route('user.dashboard')->with('success', 'Rental request submitted.');
    }
}
