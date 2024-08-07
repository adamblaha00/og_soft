<?php

declare(strict_types=1);

namespace App\Http\Controllers\TaskEstimator;

use App\Enums\CountryCodeEnum;
use App\Http\Controllers\Api\TaskEstimator\TaskEstimatorController;
use App\Models\Holiday;
use Database\Factories\HolidayFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class TaskEstimatorControllerTest extends TestCase
{
    use RefreshDatabase;

    private Holiday $model;

    protected function setUp(): void
    {
        parent::setUp();

        $this->model = HolidayFactory::new([
            'date' => '2024-08-05',
            'country' => CountryCodeEnum::CZ
        ])->createOne();
    }

    public function testEstimateCompletionEndpoint(): void
    {
        $query = [
            'start_time' => '2024-08-04 08:00:00',
            'work_start_time' => '09:00:00',
            'work_end_time' => '17:00:00',
            'duration_minutes' => 1440,
            'consider_work_days' => 1,
            'country' => CountryCodeEnum::CZ
        ];

        $response = $this->post(URL::action(TaskEstimatorController::class, $query));

        $response->assertOk();

        $response->assertJson([
            'end_date_time' => '2024-08-08 17:00:00',
        ]);
    }
}
