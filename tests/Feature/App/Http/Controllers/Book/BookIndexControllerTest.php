<?php

declare(strict_types=1);

namespace App\Http\Controllers\Book;

use App\Http\Controllers\Api\Book\BookIndexController;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class BookIndexControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testIndexBook(): void
    {
        $model = UserFactory::new()->withBooks()->createOne();

        $relatedModel = $model->books()->inRandomOrder()->first();

        $query = [
            'filter' => [
                'id' => [$relatedModel->getKey()],
                'search' => $relatedModel->name,
            ],
            'take' => BookIndexController::TAKE,
            'sort' => BookIndexController::SORT,
            'page' => 1,
        ];

        $response = $this->get(URL::action(BookIndexController::class, $query));

        $response->assertOk();

        $responseContentArray = json_decode($response->getContent(), true);

        $this->assertEquals(1, $responseContentArray['total']);
        $this->assertArrayHasKey('reviews', $responseContentArray['data'][0]);
        $this->assertArrayHasKey('user', $responseContentArray['data'][0]);
    }
}
