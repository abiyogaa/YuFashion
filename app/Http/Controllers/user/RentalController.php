<?php

namespace App\Http\Controllers\User;

use App\Models\Rental;
use App\Models\ClothingItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\RentalService;
use Illuminate\Support\Facades\Log;
use Exception;

class RentalController extends Controller
{
    protected $rentalService;

    public function __construct(RentalService $rentalService)
    {
        $this->rentalService = $rentalService;
    }

    public function index()
    {
        try {
            $activeRentals = $this->rentalService->getActiveRentalsForUser(auth()->id());
            $historyRentals = $this->rentalService->getHistoryRentalsForUser(auth()->id());
            return view('user.rentals.index', compact('activeRentals', 'historyRentals'));
        } catch (Exception $e) {
            Log::error('Error in RentalController@index: ' . $e->getMessage());
            return back()->with('error', 'Tidak dapat mengambil data penyewaan. Silakan coba lagi nanti.');
        }
    }

    public function create($clothing_item_id)
    {
        try {
            $item = ClothingItem::findOrFail($clothing_item_id);
            return view('user.rent', compact('item'));
        } catch (Exception $e) {
            Log::error('Error in RentalController@create: ' . $e->getMessage());
            return back()->with('error', 'Item yang diminta tidak dapat ditemukan.');
        }
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'clothing_item_id' => 'required|exists:clothing_items,id',
                'rental_date' => 'required|date|after_or_equal:today',
                'return_date' => 'required|date|after:rental_date',
                'total_price' => 'required|numeric|min:0',
                'quantity' => 'required|integer|min:1|max:2',
            ]);

            $this->rentalService->createRental(auth()->id(), $validatedData);

            return redirect()->route('user.dashboard')->with('success', 'Permintaan penyewaan berhasil diajukan.');
        } catch (Exception $e) {
            Log::error('Error in RentalController@store: ' . $e->getMessage());
            return back()->with('error', 'Tidak dapat membuat penyewaan. Silakan coba lagi nanti.');
        }
    }
}