<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\SimpleEndpoint;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\SimpleEndpoint\SimpleEndpointPostRequest;
use Illuminate\Http\JsonResponse;

class SimpleEndpointPostController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(SimpleEndpointPostRequest $request): JsonResponse
    {
        return response()->json([
            'message' => 'Data received',
            'data' => $request->validated(),
        ]);
    }
}
