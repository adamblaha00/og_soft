<?php

namespace App\Providers;

use App\Repositories\Contracts\HolidayRepositoryInterface;
use App\Repositories\Contracts\WorkingDayRepositoryInterface;
use App\Repositories\HolidayRepository;
use App\Repositories\WorkingDayRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(HolidayRepositoryInterface::class, HolidayRepository::class);
        $this->app->bind(WorkingDayRepositoryInterface::class, WorkingDayRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
