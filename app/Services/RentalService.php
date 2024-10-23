<?php

namespace App\Services;

use App\Models\Rental;
use App\Models\ClothingItem;
use App\Models\RentalReturn;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Carbon\Carbon;

class RentalService
{
    public function getActiveRentals()
    {
        return Rental::with(['user', 'clothingItem'])
            ->whereIn('status', ['pending', 'approved'])
            ->orderBy('created_at', 'desc');
    }

    public function getHistoryRentals()
    {
        return Rental::with(['user', 'clothingItem'])
            ->whereIn('status', ['returned', 'canceled'])
            ->orderBy('created_at', 'desc');
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
            $rentals = Rental::where('user_id', $userId)
                ->whereIn('status', ['pending', 'approved'])
                ->orderBy('created_at', 'desc')
                ->get();

            foreach ($rentals as $rental) {
                $rental->is_overdue = $this->isRentalOverdue($rental);
                $rental->overdue_charges = $this->calculateOverdueCharges($rental);
            }

            return $rentals;
        } catch (Exception $e) {
            Log::error('Error fetching active rentals for user: ' . $e->getMessage());
            throw new Exception('Tidak dapat mengambil data penyewaan aktif. Silakan coba lagi nanti.');
        }
    }

    public function getHistoryRentalsForUser($userId)
    {
        try {
            $rentals = Rental::with('rentalReturn')
                ->where('user_id', $userId)
                ->whereIn('status', ['returned', 'canceled'])
                ->orderBy('created_at', 'desc')
                ->get();

            foreach ($rentals as $rental) {
                if ($rental->status === 'returned') {
                    $rental->is_overdue = $this->isRentalOverdue($rental);
                    $rental->overdue_charges = $rental->rentalReturn->additional_charges;
                }
            }

            return $rentals;
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
            throw new Exception('Penyewaan tidak ditemukan.');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error rejecting rental: ' . $e->getMessage());
            throw new Exception('Tidak dapat menolak penyewaan. ' . $e->getMessage());
        }
    }

    public function returnRental($id)
    {
        DB::beginTransaction();
        try {
            $rental = Rental::findOrFail($id);
            
            if ($rental->status !== 'approved') {
                throw new Exception('Penyewaan ini tidak dalam status disetujui.');
            }
            
            $additionalCharges = $this->calculateOverdueCharges($rental);
            $totalPriceWithCharges = $rental->total_price + $additionalCharges;

            RentalReturn::create([
                'rental_id' => $rental->id,
                'returned_date' => Carbon::now(),
                'additional_charges' => $additionalCharges,
                'total_price_with_charges' => $totalPriceWithCharges,
            ]);
            
            $clothingItem = $rental->clothingItem;
            $clothingItem->stock += $rental->quantity;
            $clothingItem->save();

            $rental->status = 'returned';
            $rental->save();


            DB::commit();
            return $rental;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error returning rental: ' . $e->getMessage());
            throw new Exception('Tidak dapat mengembalikan penyewaan. Silakan coba lagi nanti.');
        }
    }

    private function isRentalOverdue(Rental $rental)
    {
        try {
            if (!$rental || !$rental->return_date) {
                return false;
            }

            if ($rental->status === 'approved') {
                $now = Carbon::now();
                $returnDate = Carbon::parse($rental->return_date);
                return $now->isAfter($returnDate);
            } else if ($rental->status === 'returned' && $rental->rentalReturn) {
                $returnedDate = Carbon::parse($rental->rentalReturn->returned_date);
                $dueDate = Carbon::parse($rental->return_date);
                return $returnedDate->isAfter($dueDate);
            }
            
            return false;
        } catch (Exception $e) {
            Log::error('Error checking if rental is overdue: ' . $e->getMessage());
            return false;
        }
    }

    private function calculateOverdueCharges(Rental $rental)
    {
        try {
            if (!$this->isRentalOverdue($rental)) {
                return 0;
            }

            $returnDate = Carbon::parse($rental->return_date)->startOfDay();
            $now = Carbon::now()->startOfDay();
            $daysOverdue = $now->diffInDays($returnDate);
            $overdueCharges = -$daysOverdue * 10000;

            return $overdueCharges;
        } catch (Exception $e) {
            Log::error('Error calculating overdue charges: ' . $e->getMessage());
            return 0;
        }
    }
}
