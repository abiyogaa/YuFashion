<?php

namespace App\Services;

use App\Models\Rental;
use App\Models\ClothingItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

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
            throw new Exception('Tidak dapat mengambil data penyewaan yang tertunda. Silakan coba lagi nanti.');
        }
    }

    public function getActiveRentalsForUser($userId)
    {
        try {
            return Rental::where('user_id', $userId)
                ->whereIn('status', ['pending', 'approved'])
                ->orderBy('created_at', 'desc')
                ->get();
        } catch (Exception $e) {
            Log::error('Error fetching active rentals for user: ' . $e->getMessage());
            throw new Exception('Tidak dapat mengambil data penyewaan aktif. Silakan coba lagi nanti.');
        }
    }

    public function getHistoryRentalsForUser($userId)
    {
        try {
            return Rental::where('user_id', $userId)
                ->whereIn('status', ['returned', 'canceled'])
                ->orderBy('created_at', 'desc')
                ->get();
        } catch (Exception $e) {
            Log::error('Error fetching rental history for user: ' . $e->getMessage());
            throw new Exception('Tidak dapat mengambil riwayat penyewaan. Silakan coba lagi nanti.');
        }
    }

    public function createRental($userId, $data)
    {
        DB::beginTransaction();
        try {
            $activeRentals = $this->getActiveRentalsForUser($userId)->count();

            if ($activeRentals >= 2) {
                throw new Exception('Anda telah mencapai jumlah maksimum penyewaan aktif.');
            }

            $clothingItem = ClothingItem::findOrFail($data['clothing_item_id']);
            
            if ($clothingItem->stock < $data['quantity']) {
                throw new Exception('Stok tidak mencukupi untuk item ini.');
            }

            $rental = Rental::create([
                'user_id' => $userId,
                'clothing_item_id' => $data['clothing_item_id'],
                'rental_date' => $data['rental_date'],
                'return_date' => $data['return_date'],
                'total_price' => $data['total_price'],
                'quantity' => $data['quantity'],
                'status' => 'pending',
            ]);

            $clothingItem->stock -= $data['quantity'];
            $clothingItem->save();

            DB::commit();
            return $rental;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error creating rental: ' . $e->getMessage());
            throw new Exception('Tidak dapat membuat penyewaan. ' . $e->getMessage());
        }
    }

    public function approveRental($id)
    {
        DB::beginTransaction();
        try {
            $rental = Rental::findOrFail($id);
            
            if ($rental->status !== 'pending') {
                throw new Exception('Penyewaan ini tidak dalam status tertunda.');
            }

            $rental->status = 'approved';
            $rental->save();

            DB::commit();
            return $rental;
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            Log::error('Rental not found: ' . $e->getMessage());
            throw new Exception('Penyewaan tidak ditemukan.');
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
                throw new Exception('Penyewaan ini tidak dalam status tertunda.');
            }

            $clothingItem = $rental->clothingItem;
            $clothingItem->stock += $rental->quantity;
            $clothingItem->save();

            $rental->status = 'canceled';
            $rental->save();

            DB::commit();
            return $rental;
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            Log::error('Rental not found: ' . $e->getMessage());
            throw new Exception('Rental not found.');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error rejecting rental: ' . $e->getMessage());
            throw new Exception('Unable to reject rental. ' . $e->getMessage());
        }
    }

    public function returnRental($id)
    {
        DB::beginTransaction();
        try {
            $rental = Rental::findOrFail($id);
            
            if ($rental->status !== 'approved') {
                throw new Exception('This rental is not in approved status.');
            }

            $clothingItem = $rental->clothingItem;
            $clothingItem->stock += $rental->quantity;
            $clothingItem->save();

            $rental->status = 'returned';
            $rental->save();

            DB::commit();
            return $rental;
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            Log::error('Rental not found: ' . $e->getMessage());
            throw new Exception('Rental not found.');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error returning rental: ' . $e->getMessage());
            throw new Exception('Unable to return rental. ' . $e->getMessage());
        }
    }

    public function checkOverdueRentals()
    {
        try {
            $overdueRentals = Rental::where('status', 'approved')
                ->where('return_date', '<', Carbon::now()->toDateString())
                ->get();

            foreach ($overdueRentals as $rental) {
                $daysOverdue = Carbon::now()->diffInDays(Carbon::parse($rental->return_date));
                $overdueCharge = $daysOverdue * 10000;
                
                $rental->total_price += $overdueCharge;
                $rental->save();

                Log::info('Overdue rental found: ' . $rental->id . '. Added charge: $' . $overdueCharge);
            }

            return $overdueRentals;
        } catch (Exception $e) {
            Log::error('Error checking overdue rentals: ' . $e->getMessage());
            throw new Exception('Unable to check overdue rentals. ' . $e->getMessage());
        }
    }
}