<?php

declare(strict_types=1);

namespace App\Services;

use App\DTO\TaskEstimateDTO;
use App\Enums\CountryCodeEnum;
use App\Repositories\HolidayRepository;
use Carbon\Carbon;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class TaskEstimatorServiceTest extends TestCase
{
    protected TaskEstimatorService $taskEstimatorService;
    private HolidayRepository&MockObject $holidayRepositoryMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->holidayRepositoryMock = $this->createMock(HolidayRepository::class);
    }

    public function testEstimateCompletionWithoutWorkDays(): void
    {
        $startTime = Carbon::createFromFormat('Y-m-d H:i:s', '2024-08-04 08:00:00');

        $durationMinutes = 480;
        $taskEstimateDTO = new TaskEstimateDTO(
            CountryCodeEnum::CZ,
            Carbon::createFromFormat('H:i:s', '09:00:00')->setDateFrom($startTime),
            Carbon::createFromFormat('H:i:s', '17:00:00')->setDateFrom($startTime),
            $startTime,
            false,
            $durationMinutes
        );

        $endTime = (new TaskEstimatorService($this->holidayRepositoryMock))->estimateCompletion($taskEstimateDTO);

        $this->assertEquals('2024-08-04 16:00:00', $endTime->toDateTimeString());
    }

    public function testEstimateCompletionWithWorkDays(): void
    {
        $startTime = Carbon::createFromFormat('Y-m-d H:i:s', '2024-08-04 08:00:00');
        $durationMinutes = 480;
        $holidays = collect();

        $this->holidayRepositoryMock
            ->method('getHolidaysByCountry')
            ->willReturn($holidays);

        $taskEstimateDTO = new TaskEstimateDTO(
            CountryCodeEnum::CZ,
            Carbon::createFromFormat('H:i:s', '09:00:00')->setDateFrom($startTime),
            Carbon::createFromFormat('H:i:s', '17:00:00')->setDateFrom($startTime),
            $startTime,
            true,
            $durationMinutes
        );

        $endTime = (new TaskEstimatorService($this->holidayRepositoryMock))->estimateCompletion($taskEstimateDTO);

        $this->assertEquals('2024-08-05 17:00:00', $endTime->toDateTimeString());
    }

    public function testEstimateCompletionOverMultipleDays(): void
    {
        $startTime = Carbon::createFromFormat('Y-m-d H:i:s', '2024-08-04 10:00:00');
        $durationMinutes = 1530;
        $holidays = collect();

        $this->holidayRepositoryMock
            ->method('getHolidaysByCountry')
            ->willReturn($holidays);

        $taskEstimateDTO = new TaskEstimateDTO(
            CountryCodeEnum::CZ,
            Carbon::createFromFormat('H:i:s', '09:00:00')->setDateFrom($startTime),
            Carbon::createFromFormat('H:i:s', '17:00:00')->setDateFrom($startTime),
            $startTime,
            true,
            $durationMinutes
        );

        $endTime = (new TaskEstimatorService($this->holidayRepositoryMock))->estimateCompletion($taskEstimateDTO);

        $this->assertEquals('2024-08-08 10:30:00', $endTime->toDateTimeString());
    }

    public function testEstimateCompletionWithHoliday(): void
    {
        $startTime = Carbon::createFromFormat('Y-m-d H:i:s', '2024-08-04 08:00:00');
        $durationMinutes = 1440;
        $holidays = collect([Carbon::createFromFormat('Y-m-d', '2024-08-05')]);

        $this->holidayRepositoryMock
            ->method('getHolidaysByCountry')
            ->willReturn($holidays);

        $taskEstimateDTO = new TaskEstimateDTO(
            CountryCodeEnum::CZ,
            Carbon::createFromFormat('H:i:s', '09:00:00')->setDateFrom($startTime),
            Carbon::createFromFormat('H:i:s', '17:00:00')->setDateFrom($startTime),
            $startTime,
            true,
            $durationMinutes
        );

        $endTime = (new TaskEstimatorService($this->holidayRepositoryMock))->estimateCompletion($taskEstimateDTO);

        $this->assertEquals('2024-08-08 17:00:00', $endTime->toDateTimeString());
    }
}
