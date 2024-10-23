<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Rental;
use App\Models\ClothingItem;
use App\Models\User;
use Carbon\Carbon;
use InvalidArgumentException;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Rental>
 */
class RentalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Rental::class;
    public function definition(): array
    {
        $rentalDate = Carbon::instance(fake()->dateTimeBetween('-1 month', 'now'));
        if (!$rentalDate) {
            throw new InvalidArgumentException('Failed to generate valid rental date');
        }

        $returnDate = Carbon::instance($rentalDate)->addDays(fake()->numberBetween(1, 14));
        if (!$returnDate || $returnDate->lte($rentalDate)) {
            throw new InvalidArgumentException('Return date must be after rental date');
        }

        $quantity = fake()->numberBetween(1, 3);
        if ($quantity < 1) {
            throw new InvalidArgumentException('Quantity must be at least 1');
        }
        
        $clothingItem = ClothingItem::inRandomOrder()->first();
        if (!$clothingItem) {
            throw new InvalidArgumentException('No clothing items available in the database');
        }

        $user = User::inRandomOrder()->first();
        if (!$user) {
            throw new InvalidArgumentException('Failed to create user');
        }
        
        $totalPrice = $clothingItem->price * $quantity;
        if ($totalPrice < 0) {
            throw new InvalidArgumentException('Total price cannot be negative');
        }

        $validStatuses = ['pending', 'approved', 'returned', 'canceled'];
        $status = fake()->randomElement($validStatuses);
        if (!in_array($status, $validStatuses)) {
            throw new InvalidArgumentException('Invalid rental status');
        }

        return [
            'user_id' => $user->id,
            'clothing_item_id' => $clothingItem->id,
            'rental_date' => $rentalDate->toDateString(),
            'return_date' => $returnDate->toDateString(),
            'quantity' => $quantity,
            'total_price' => $totalPrice,
            'status' => $status,
        ];
    }

    /**
     * Indicate that the rental is pending.
     */
    public function pending(): static
    {
        return $this->state(function (array $attributes) {
            $this->validateDates($attributes);
            return ['status' => 'pending'];
        });
    }

    /**
     * Indicate that the rental is approved.
     */
    public function approved(): static
    {
        return $this->state(function (array $attributes) {
            $this->validateDates($attributes);
            return ['status' => 'approved'];
        });
    }

    /**
     * Indicate that the rental is returned.
     */
    public function returned(): static
    {
        return $this->state(function (array $attributes) {
            $this->validateDates($attributes);
            return ['status' => 'returned'];
        });
    }

    /**
     * Indicate that the rental is canceled.
     */
    public function canceled(): static
    {
        return $this->state(function (array $attributes) {
            $this->validateDates($attributes);
            return ['status' => 'canceled'];
        });
    }

    /**
     * Validate rental and return dates
     */
    private function validateDates(array $attributes): void
    {
        $rentalDate = Carbon::parse($attributes['rental_date']);
        $returnDate = Carbon::parse($attributes['return_date']);

        if ($returnDate->lte($rentalDate)) {
            throw new InvalidArgumentException('Return date must be after rental date');
        }

        $now = Carbon::now();
        if ($rentalDate->gt($now)) {
            throw new InvalidArgumentException('Rental date cannot be in the future');
        }
    }
}
