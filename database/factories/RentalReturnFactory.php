<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\RentalReturn;
use App\Models\Rental;
use Carbon\Carbon;
use InvalidArgumentException;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RentalReturn>
 */
class RentalReturnFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = RentalReturn::class;
    public function definition(): array
    {
        $rental = Rental::inRandomOrder()->where('status', 'returned')->first();
        if (!$rental) {
            $rental = Rental::factory()->returned()->create();
        }

        if (!$rental) {
            throw new InvalidArgumentException('Failed to create rental record');
        }

        $returnedDate = Carbon::instance(fake()->dateTimeBetween($rental->rental_date, $rental->return_date));
        if (!$returnedDate) {
            throw new InvalidArgumentException('Invalid returned date');
        }

        $this->validateDates($rental->rental_date, $rental->return_date, $returnedDate);

        return [
            'rental_id' => $rental->id,
            'returned_date' => $returnedDate->toDateString(),
            'additional_charges' => 0,
            'total_price_with_charges' => $rental->total_price,
        ];
    }

    /**
     * Indicate that the return is late.
     */
    public function late(): static
    {
        return $this->state(function (array $attributes) {
            $rental = Rental::findOrFail($attributes['rental_id']);
            
            if (!$rental->return_date) {
                throw new InvalidArgumentException('Rental return date is not set');
            }

            $lateDate = Carbon::instance(fake()->dateTimeBetween($rental->return_date, Carbon::parse($rental->return_date)->addMonth()));
            if (!$lateDate) {
                throw new InvalidArgumentException('Invalid late return date');
            }

            $this->validateDates($rental->rental_date, $rental->return_date, $lateDate);

            $daysLate = Carbon::parse($rental->return_date)->diffInDays($lateDate);
            $lateCharges = $daysLate * 10000;

            return [
                'returned_date' => $lateDate->toDateString(),
                'additional_charges' => $lateCharges,
                'total_price_with_charges' => $rental->total_price + $lateCharges,
            ];
        });
    }

    /**
     * Validate rental, return, and returned dates
     */
    private function validateDates(string $rentalDate, string $returnDate, Carbon $returnedDate): void
    {
        $rentalDate = Carbon::parse($rentalDate);
        $returnDate = Carbon::parse($returnDate);

        if ($returnDate->lte($rentalDate)) {
            throw new InvalidArgumentException('Return date must be after rental date');
        }

        if ($returnedDate->lt($rentalDate)) {
            throw new InvalidArgumentException('Returned date cannot be before rental date');
        }

        $now = Carbon::now();
        if ($rentalDate->gt($now)) {
            throw new InvalidArgumentException('Rental date cannot be in the future');
        }
    }
}
