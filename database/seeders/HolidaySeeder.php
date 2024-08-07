<?php

namespace Database\Seeders;

use App\Models\Holiday;
use Database\Factories\HolidayFactory;
use Illuminate\Database\Seeder;

class HolidaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Holiday::query()->getQuery()->exists()) {
            return;
        }

        HolidayFactory::new()
            ->count(100)
            ->create();
    }
}
