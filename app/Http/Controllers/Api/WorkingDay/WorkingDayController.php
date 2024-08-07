<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\WorkingDay;

use App\Enums\CountryCodeEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\WorkingDay\WorkingDayRequest;
use App\Services\WorkingDayService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class WorkingDayController extends Controller
{
    public function __construct(private readonly WorkingDayService $workingDayService)
    {
    }

    /**
     * Handle the incoming request.
     */
    public function __invoke(WorkingDayRequest $request): JsonResponse
    {
        $date = $request->string('date')->value();

        $isWorkingDay = $this->workingDayService->isWorkingDay(
            $date,
            $request->string('country')->value()
        );

        return response()->json([
            'date' => $date,
            'is_working_day' => $isWorkingDay,
        ]);
    }
}
