<?php

declare(strict_types=1);

namespace App\Http\Controllers\SimpleEndpoint;

use App\Http\Controllers\Api\SimpleEndpoint\SimpleEndpointGetController;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class SimpleEndpointGetControllerTest extends TestCase
{
    public function testSimpleGetEndpoint(): void
    {
        $response = $this->get(URL::action(SimpleEndpointGetController::class));

        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Hello, world!',
        ]);
    }
}
