<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\CountryCodeEnum;
use App\Models\Holiday;
use App\Repositories\HolidayRepository;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;

class WorkingDayServiceTest extends TestCase
{
    private HolidayRepository&MockObject $holidayRepositoryMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->holidayRepositoryMock = $this->createMock(HolidayRepository::class);
    }

    public function testIsHolidayDate(): void
    {
        $holiday = new Holiday([
            'date' => Carbon::now(),
            'country' => Arr::random(CountryCodeEnum::values())
        ]);

        $this->holidayRepositoryMock
            ->expects($this->once())
            ->method('findByDateAndCountry')
            ->with($holiday->getDate(), $holiday->getCountry())
            ->willReturn($holiday);

        $result = (new WorkingDayService($this->holidayRepositoryMock))
            ->isWorkingDay(
                $holiday->getDate()->format('Y-m-d'),
                $holiday->getCountry()->value
            );

        $this->assertFalse($result);
    }

    public function testIsNotHolidayDate(): void
    {
        $holiday = new Holiday([
            'date' => Carbon::now(),
            'country' => Arr::random(CountryCodeEnum::values())
        ]);

        $this->holidayRepositoryMock
            ->expects($this->once())
            ->method('findByDateAndCountry')
            ->with(Carbon::tomorrow(), $holiday->getCountry())
            ->willReturn(null);

        $result = (new WorkingDayService($this->holidayRepositoryMock))
            ->isWorkingDay(
                Carbon::tomorrow()->format('Y-m-d'),
                $holiday->getCountry()->value
            );

        $this->assertTrue($result);
    }
}
