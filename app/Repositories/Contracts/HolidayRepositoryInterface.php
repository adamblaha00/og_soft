<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\Enums\CountryCodeEnum;
use App\Models\Holiday;
use Carbon\Carbon;
use Illuminate\Support\Collection;

interface HolidayRepositoryInterface
{
    public function findByDateAndCountry(Carbon $date, CountryCodeEnum $countryCode): ?Holiday;

    public function getHolidaysByCountry(string $country): Collection;
}
