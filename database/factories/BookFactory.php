<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Book;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => UserFactory::new(),
            'name' => $this->faker->sentence,
            'summary' => $this->faker->paragraph,
        ];
    }

    /**
     * Generate model with reviews.
     */
    public function withReviews(): static
    {
        return $this->afterCreating(static function (Model $model): void {
            \assert($model instanceof Book);

            for ($i = 0; $i < 5; ++$i) {
                ReviewFactory::new(['book_id' => $model->getKey()])->createOne();
            }
        });
    }
}
