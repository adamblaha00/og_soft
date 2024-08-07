<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\CountryCodeEnum;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Holiday>
 */
class HolidayFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'date' => $this->faker->date(),
            'country' => fake()->randomElement(CountryCodeEnum::values()),
        ];
    }
}
