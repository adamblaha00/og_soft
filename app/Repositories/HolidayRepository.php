<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Enums\CountryCodeEnum;
use App\Models\Holiday;
use App\Repositories\Contracts\HolidayRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class HolidayRepository implements HolidayRepositoryInterface
{
    public function findByDateAndCountry(Carbon $date, CountryCodeEnum $countryCode): ?Holiday
    {
        return Holiday::query()
            ->where('country', '=', $countryCode)
            ->where('date', '=', $date)
            ->first();
    }

    public function getHolidaysByCountry(string $country): Collection
    {
        return Holiday::query()
            ->where('country', $country)
            ->get()
            ->pluck('date');
    }
}
