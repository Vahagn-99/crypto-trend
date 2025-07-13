<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register() : void
    {
    }

    /**
     * Bootstrap any application services.
     */
    public function boot() : void
    {
        $schedule_service = resolve(\App\Console\Schedule::class);

        $schedule_service->run($this->app->make(\Illuminate\Console\Scheduling\Schedule::class));
    }
}
