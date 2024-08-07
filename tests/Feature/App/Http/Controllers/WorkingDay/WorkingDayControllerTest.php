<?php

declare(strict_types=1);

namespace App\Http\Controllers\WorkingDay;

use App\Http\Controllers\Api\SimpleEndpoint\SimpleEndpointPostController;
use App\Http\Controllers\Api\WorkingDay\WorkingDayController;
use App\Models\Holiday;
use Database\Factories\HolidayFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class WorkingDayControllerTest extends TestCase
{
    use RefreshDatabase;

    private Holiday $model;

    protected function setUp(): void
    {
        parent::setUp();

        $this->model = HolidayFactory::new()->createOne();
    }

    public function testWorkingDay(): void
    {
        $workingDay = $this->model->getDate()->addDay()->format('Y-m-d');

        $query = [
            'date' => $workingDay,
            'country' => $this->model->getCountry(),
        ];

        $response = $this->post(URL::action(WorkingDayController::class, $query));

        $response->assertOk();

        $response->assertJson([
            'date' => $workingDay,
            'is_working_day' => true,
        ]);
    }

    public function testHolidayDay(): void
    {
        $query = [
            'date' => $this->model->getDate()->format('Y-m-d'),
            'country' => $this->model->getCountry(),
        ];

        $response = $this->post(URL::action(WorkingDayController::class, $query));

        $response->assertOk();

        $response->assertJson([
            'date' => $this->model->getDate()->format('Y-m-d'),
            'is_working_day' => false,
        ]);
    }

    public function testDataEndpointValidationErrors(): void
    {
        $query = [
            'date' => 1879,
        ];

        $response = $this->post(URL::action(WorkingDayController::class, $query));

        $response->assertUnprocessable();
        $this->assertEquals([
            'date' => ['The date field must be a valid date.'],
            'country' => ['The country field is required.'],
        ], $response->json('errors'));
    }
}
