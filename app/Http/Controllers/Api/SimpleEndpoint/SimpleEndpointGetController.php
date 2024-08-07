<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\SimpleEndpoint;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class SimpleEndpointGetController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): JsonResponse
    {
        return response()->json(['message' => 'Hello, world!']);
    }
}
