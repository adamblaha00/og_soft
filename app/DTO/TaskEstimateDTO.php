<?php

declare(strict_types=1);

namespace App\DTO;

use App\Enums\CountryCodeEnum;
use Carbon\Carbon;

readonly class TaskEstimateDTO
{
    public function __construct(
        public CountryCodeEnum $country,
        public Carbon $workStartTime,
        public Carbon $workEndTime,
        public Carbon $startTime,
        public bool $considerWorkDays,
        public int $durationMinutes
    ) {
    }
}
