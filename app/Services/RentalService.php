<?php

namespace App\Services;

use App\Models\Rental;
use Illuminate\Support\Facades\DB;
use Exception;

class RentalService
{
    public function getAllRentals()
    {
        return Rental::with(['user', 'clothingItem'])->orderBy('created_at', 'desc')->get();
    }

    public function getAllPendingRentals()
    {
        return Rental::with(['user', 'clothingItem'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function approveRental($id)
    {
        DB::beginTransaction();
        try {
            $rental = Rental::findOrFail($id);
            $rental->status = 'approved';
            $rental->save();

            $clothingItem = $rental->clothingItem;
            $clothingItem->stock -= 1;
            $clothingItem->save();

            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    public function rejectRental($id)
    {
        try {
            $rental = Rental::findOrFail($id);
            $rental->status = 'rejected';
            $rental->save();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}