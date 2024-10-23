<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\RentalService;
use Illuminate\Support\Facades\Log;
use Exception;

class ManageRentalController extends Controller
{
    protected $rentalService;

    public function __construct(RentalService $rentalService)
    {
        $this->rentalService = $rentalService;
    }

    public function index()
    {
        try {
            $rentals = $this->rentalService->getAllRentals();
            return view('admin.rentals.index', compact('rentals'));
        } catch (Exception $e) {
            Log::error('Error fetching rentals: ' . $e->getMessage());
            return view('admin.error', ['message' => $e->getMessage()]);
        }
    }

    public function approve($id)
    {
        try {
            $rental = $this->rentalService->approveRental($id);
            return redirect()->route('admin.rentals.index')->with('success', 'Penyewaan berhasil disetujui.');
        } catch (Exception $e) {
            Log::error('Error approving rental: ' . $e->getMessage(), ['rental_id' => $id]);
            return redirect()->route('admin.rentals.index')->with('error', $e->getMessage());
        }
    }

    public function reject($id)
    {
        try {
            $rental = $this->rentalService->rejectRental($id);
            return redirect()->route('admin.rentals.index')->with('success', 'Penyewaan berhasil ditolak.');
        } catch (Exception $e) {
            Log::error('Error rejecting rental: ' . $e->getMessage(), ['rental_id' => $id]);
            return redirect()->route('admin.rentals.index')->with('error', $e->getMessage());
        }
    }

    public function return($id)
    {
        try {
            $rental = $this->rentalService->returnRental($id);
            return redirect()->route('admin.rentals.index')->with('success', 'Penyewaan berhasil dikembalikan.');
        } catch (Exception $e) {
            Log::error('Error returning rental: ' . $e->getMessage(), ['rental_id' => $id]);
            return redirect()->route('admin.rentals.index')->with('error', $e->getMessage());
        }
    }
}