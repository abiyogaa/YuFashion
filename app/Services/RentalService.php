<?php

namespace App\Services;

use App\Models\Rental;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class RentalService
{
    public function getAllRentals()
    {
        try {
            return Rental::with(['user', 'clothingItem'])
                ->orderBy('created_at', 'desc')
                ->get();
        } catch (Exception $e) {
            Log::error('Error fetching all rentals: ' . $e->getMessage());
            throw new Exception('Tidak dapat mengambil data penyewaan. Silakan coba lagi nanti.');
        }
    }

    public function getAllPendingRentals()
    {
        try {
            return Rental::with(['user', 'clothingItem'])
                ->where('status', 'pending')
                ->orderBy('created_at', 'desc')
                ->get();
        } catch (Exception $e) {
            Log::error('Error fetching pending rentals: ' . $e->getMessage());
            throw new Exception('Tidak dapat mengambil data penyewaan yang pending. Silakan coba lagi nanti.');
        }
    }

    public function approveRental($id)
    {
        DB::beginTransaction();
        try {
            $rental = Rental::findOrFail($id);
            
            if ($rental->status !== 'pending') {
                throw new Exception('Penyewaan ini tidak dalam status pending.');
            }

            $clothingItem = $rental->clothingItem;
            
            if ($clothingItem->stock <= 0) {
                throw new Exception('Barang ini sedang tidak tersedia.');
            }

            $rental->status = 'approved';
            $rental->save();

            $clothingItem->stock -= 1;
            $clothingItem->save();

            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error approving rental: ' . $e->getMessage());
            throw new Exception('Tidak dapat menyetujui penyewaan. ' . $e->getMessage());
        }
    }

    public function rejectRental($id)
    {
        DB::beginTransaction();
        try {
            $rental = Rental::findOrFail($id);
            
            if ($rental->status !== 'pending') {
                throw new Exception('Penyewaan ini tidak dalam status pending.');
            }

            $rental->status = 'rejected';
            $rental->save();

            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error rejecting rental: ' . $e->getMessage());
            throw new Exception('Tidak dapat menolak penyewaan. ' . $e->getMessage());
        }
    }
}