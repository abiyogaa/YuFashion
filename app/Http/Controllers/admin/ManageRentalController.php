<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\RentalService;
use Illuminate\Http\Request;

class ManageRentalController extends Controller
{
    protected $rentalService;

    public function __construct(RentalService $rentalService)
    {
        $this->rentalService = $rentalService;
    }

    public function index()
    {
        $rentals = $this->rentalService->getAllPendingRentals();
        return view('admin.rentals.index', compact('rentals'));
    }

    public function approve($id)
    {
        $result = $this->rentalService->approveRental($id);
        if ($result) {
            return redirect()->route('admin.rentals.index')->with('success', 'Rental approved successfully.');
        }
        return redirect()->route('admin.rentals.index')->with('error', 'Failed to approve rental.');
    }

    public function reject($id)
    {
        $result = $this->rentalService->rejectRental($id);
        if ($result) {
            return redirect()->route('admin.rentals.index')->with('success', 'Rental rejected successfully.');
        }
        return redirect()->route('admin.rentals.index')->with('error', 'Failed to reject rental.');
    }
}