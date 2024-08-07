<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\TaskEstimator;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\TaskEstimator\TaskEstimatorRequest;
use App\Services\TaskEstimatorService;
use App\Transformers\TaskEstimatorRequestTransformer;
use Illuminate\Http\JsonResponse;

class TaskEstimatorController extends Controller
{
    public function __construct(
        protected TaskEstimatorRequestTransformer $taskEstimatorRequestTransformer,
        protected TaskEstimatorService $taskEstimatorService,
    ) {
    }

    /**
     * Handle the incoming request.
     */
    public function __invoke(TaskEstimatorRequest $request): JsonResponse
    {
        $taskEstimateDTO = $this->taskEstimatorRequestTransformer->transformFromRequest(
            $request->string('work_start_time')->value(),
            $request->string('work_end_time')->value(),
            $request->string('start_time')->value(),
            $request->string('country')->value(),
            $request->boolean('consider_work_days'),
            $request->integer('duration_minutes')
        );

        $endTime = $this->taskEstimatorService->estimateCompletion($taskEstimateDTO);

        return response()->json([
            'end_date_time' => $endTime->toDateTimeString(),
        ]);
    }
}
