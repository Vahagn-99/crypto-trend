<?php

namespace App\Providers;

use App\Base\Auth\Generators\ICodeGenerator;
use App\Base\Auth\Generators\LocalEnvironmentCodeGenerator;
use App\Base\Auth\Generators\SixDigitCodeGenerator;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class OPTServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register() : void
    {

        $this->app->scoped(ICodeGenerator::class, function (Application $app) {
           return match ($app->environment()) {
                'local' => $app->make(LocalEnvironmentCodeGenerator::class),
                default => $app->make(SixDigitCodeGenerator::class),
            };
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
