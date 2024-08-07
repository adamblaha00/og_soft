<?php

declare(strict_types=1);

namespace App\Transformers;

use App\DTO\TaskEstimateDTO;
use App\Enums\CountryCodeEnum;
use Carbon\Carbon;

class TaskEstimatorRequestTransformer
{
    public function transformFromRequest(
        string $workStartTime,
        string $workEndTime,
        string $startTime,
        string $country,
        bool $considerWorkDays,
        int $durationMinutes,
    ): TaskEstimateDTO {
        $startTimeCarbon = Carbon::createFromFormat('Y-m-d H:i:s', $startTime);

        return new TaskEstimateDTO(
            CountryCodeEnum::findByCountry($country),
            Carbon::createFromFormat('H:i:s', $workStartTime)?->setDateFrom($startTimeCarbon),
            Carbon::createFromFormat('H:i:s', $workEndTime)?->setDateFrom($startTimeCarbon),
            $startTimeCarbon,
            $considerWorkDays,
            $durationMinutes
        );
    }
}
