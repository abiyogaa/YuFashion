<?php

namespace App\Http\Controllers\User;

use App\Models\Rental;
use App\Models\ClothingItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RentalController extends Controller
{
    public function index()
    {
        // Ambil peminjaman aktif
        $activeRentals = Rental::where('user_id', auth()->id())
            ->whereIn('status', ['pending', 'approved'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Ambil riwayat peminjaman
        $historyRentals = Rental::where('user_id', auth()->id())
            ->whereIn('status', ['returned', 'canceled'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('user.rentals.index', compact('activeRentals', 'historyRentals'));
    }

    public function create($clothing_item_id)
    {
        $item = ClothingItem::findOrFail($clothing_item_id);
        return view('user.rent', compact('item'));
    }

    public function store(Request $request)
{
    // Validasi input
    $request->validate([
        'rental_date' => 'required|date',
        'return_date' => 'required|date|after:rental_date',
        'total_price' => 'required|integer',
    ]);

    // Cek jumlah peminjaman aktif
    $activeRentals = Rental::where('user_id', auth()->user()->id)
        ->whereIn('status', ['pending', 'approved'])
        ->count();

    if ($activeRentals >= 2) {
        return redirect()->back()->with('error', 'You cannot have more than 2 active rentals.');
    }

    // Jika tidak lebih dari 2, simpan peminjaman
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
