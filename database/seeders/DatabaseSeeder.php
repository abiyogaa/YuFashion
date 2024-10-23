<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Rental;
use App\Models\RentalReturn;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RoleSeeder::class);

        User::factory()->admin()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
        ]);

        User::factory()->count(10)->create();

        $this->call(CategorySeeder::class);
        $this->call(ClothingItemSeeder::class);

        Rental::factory()->count(5)->pending()->create();
        Rental::factory()->count(5)->approved()->create();
        Rental::factory()->count(5)->canceled()->create();

        Rental::factory()
            ->count(10)
            ->returned()
            ->create()
            ->each(function ($rental) {
                if (fake()->boolean(30)) {
                    RentalReturn::factory()->late()->create(['rental_id' => $rental->id]);
                } else {
                    RentalReturn::factory()->create(['rental_id' => $rental->id]);
                }
            });
    }
}
