<?php

namespace App\Providers;

use App\Base\Coin\CryptoMarket\CoingeckoCryptoMarketManager;
use App\Base\Coin\CryptoMarket\ICryptoMarketManager;
use Illuminate\Foundation\Application;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\ServiceProvider;

class CoinMarketServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register() : void
    {
        $this->app->singleton(ICryptoMarketManager::class, function (Application $app) {
            return new CoingeckoCryptoMarketManager($app->make(PendingRequest::class));
        });
    }
}
