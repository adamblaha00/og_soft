<?php

declare(strict_types=1);

namespace App\Services;

use App\DTO\TaskEstimateDTO;
use App\Repositories\Contracts\HolidayRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Collection;

readonly class TaskEstimatorService
{
    public function __construct(private HolidayRepositoryInterface $holidayRepository)
    {
    }

    public function estimateCompletion(TaskEstimateDTO $taskEstimate): Carbon
    {
        $endTime = clone $taskEstimate->startTime;
        $duration = $taskEstimate->durationMinutes;

        if (!$taskEstimate->considerWorkDays) {
            return $endTime->addMinutes($duration);
        }

        $holidays = $this->holidayRepository->getHolidaysByCountry($taskEstimate->country->value);

        while ($duration > 0) {
            if ($this->isBeforeWorkHours($endTime, $taskEstimate->workStartTime)) {
                $endTime = $endTime->setTime($taskEstimate->workStartTime->hour, $taskEstimate->workStartTime->minute, $taskEstimate->workStartTime->second);
            }

            if ($this->isAfterWorkHours($endTime, $taskEstimate->workEndTime)) {
                $endTime->addDay()->setTime($taskEstimate->workStartTime->hour, $taskEstimate->workStartTime->minute, $taskEstimate->workStartTime->second);

                continue;
            }

            if ($endTime->isWeekend() || $this->isHoliday($endTime, $holidays)) {
                $endTime->addDay()->setTime($taskEstimate->workStartTime->hour, $taskEstimate->workStartTime->minute, $taskEstimate->workStartTime->second);

                continue;
            }

            $todayWorkEndTime = (clone $endTime)->setTime($taskEstimate->workEndTime->hour, $taskEstimate->workEndTime->minute, $taskEstimate->workEndTime->second);
            $remainingTimeToday = $endTime->diffInMinutes($todayWorkEndTime);

            if ($remainingTimeToday >= $duration) {
                $endTime->addMinutes($duration);

                return $endTime;
            }

            $duration -= $remainingTimeToday;
            $endTime->addDay()->setTime($taskEstimate->workStartTime->hour, $taskEstimate->workStartTime->minute, $taskEstimate->workStartTime->second);
        }

        return $endTime;
    }

    private function isBeforeWorkHours(Carbon $currentTime, Carbon $workStartTime): bool
    {
        return $currentTime->format('H:i:s') < $workStartTime->format('H:i:s');
    }

    private function isAfterWorkHours(Carbon $currentTime, Carbon $workEndTime): bool
    {
        return $currentTime->format('H:i:s') > $workEndTime->format('H:i:s');
    }

    private function isHoliday(Carbon $currentTime, Collection $holidays): bool
    {
        return $holidays->filter(static function (Carbon $holiday) use ($currentTime) {
            return $holiday->isSameDay($currentTime);
        })->isNotEmpty();
    }
}
