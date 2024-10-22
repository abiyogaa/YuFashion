<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\RentalService;
use Illuminate\Http\Request;
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
            $rentals = $this->rentalService->getAllPendingRentals();
            return view('admin.rentals.index', compact('rentals'));
        } catch (Exception $e) {
            Log::error('Error fetching pending rentals: ' . $e->getMessage());
            return view('admin.error', ['message' => 'An error occurred while loading pending rentals.']);
        }
    }

    public function approve($id)
    {
        try {
            $result = $this->rentalService->approveRental($id);
            if ($result) {
                Log::info('Rental approved successfully', ['rental_id' => $id]);
                return redirect()->route('admin.rentals.index')->with('success', 'Rental approved successfully.');
            }
            Log::warning('Failed to approve rental', ['rental_id' => $id]);
            return redirect()->route('admin.rentals.index')->with('error', 'Failed to approve rental.');
        } catch (Exception $e) {
            Log::error('Error approving rental: ' . $e->getMessage(), ['rental_id' => $id]);
            return redirect()->route('admin.rentals.index')->with('error', 'An error occurred while approving the rental.');
        }
    }

    public function reject($id)
    {
        try {
            $result = $this->rentalService->rejectRental($id);
            if ($result) {
                Log::info('Rental rejected successfully', ['rental_id' => $id]);
                return redirect()->route('admin.rentals.index')->with('success', 'Rental rejected successfully.');
            }
            Log::warning('Failed to reject rental', ['rental_id' => $id]);
            return redirect()->route('admin.rentals.index')->with('error', 'Failed to reject rental.');
        } catch (Exception $e) {
            Log::error('Error rejecting rental: ' . $e->getMessage(), ['rental_id' => $id]);
            return redirect()->route('admin.rentals.index')->with('error', 'An error occurred while rejecting the rental.');
        }
    }
}