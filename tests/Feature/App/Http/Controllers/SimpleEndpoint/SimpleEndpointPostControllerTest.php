<?php

declare(strict_types=1);

namespace App\Http\Controllers\SimpleEndpoint;

use App\Http\Controllers\Api\SimpleEndpoint\SimpleEndpointGetController;
use App\Http\Controllers\Api\SimpleEndpoint\SimpleEndpointPostController;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class SimpleEndpointPostControllerTest extends TestCase
{
    public function testSimplePostEndpoint(): void
    {
        $response = $this->post(URL::action(SimpleEndpointPostController::class, [
            'name' => 'John Doe',
            'age' => 30,
        ]));

        $response->assertOk();
    }

    public function testDataEndpointValidationErrors(): void
    {
        $response = $this->post(URL::action(SimpleEndpointPostController::class, [
            'name' => '',
            'age' => 'not an integer',
        ]));

        $response->assertUnprocessable();
        $this->assertEquals([
            'name' => ['The name field is required.'],
            'age' => ['The age field must be an integer.'],
        ], $response->json('errors'));
    }
}
