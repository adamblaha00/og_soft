<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\CountryCodeEnum;
use App\Exceptions\WorkingDayException;
use App\Repositories\Contracts\HolidayRepositoryInterface;
use Carbon\Carbon;

readonly class WorkingDayService
{
    public function __construct(
        private HolidayRepositoryInterface $holidayRepository,
    ) {
    }

    public function isWorkingDay(string $date, string $country): bool
    {
        $date = Carbon::createFromFormat('Y-m-d', $date)->startOfDay();


        if (null === $date) {
            throw new WorkingDayException('Invalid date format');
        }

        return $this->holidayRepository->findByDateAndCountry($date, CountryCodeEnum::findByCountry($country)) === null;
    }
}
