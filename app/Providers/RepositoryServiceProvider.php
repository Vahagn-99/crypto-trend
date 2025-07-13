<?php

namespace App\Providers;

use App\Models\{
    PhoneOtp as PhoneOtpModel,
    Coin as CoinModel,
};
use App\Base\Auth\Repository\{
    IOTPRepository,
    OPTRepository,
};
use App\Base\Coin\Repository\{
    CacheDecorator,
    CoinRepository,
    ICoinRepository
};
use App\Repository\Query;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * @var array|\class-string[][]
     */
    public array $repositories = [
        IOTPRepository::class => [
            OPTRepository::class => PhoneOtpModel::class,
        ],
        ICoinRepository::class => [
            CacheDecorator::class => [
                CoinRepository::class => CoinModel::class,
            ]
        ],
    ];

    /**
     * Register services.
     */
    public function register() : void
    {
        foreach ($this->repositories as $interface => $bindings) {
            foreach ($bindings as $class => $model) {
                if (! is_array($model)) {
                    $this->app->bind($interface, function (Application $app) use ($class, $model) {
                        return $app->make($class, ['query' => new Query($model)]);
                    });
                } else {
                    foreach ($model as $key => $value) {
                        $this->app->bind($interface, function (Application $app) use ($class, $model, $key, $value) {
                            return $app->make($class, ['actual' => $app->make($key, ['query' => new Query($value)])]);
                        });
                    }
                }
            }
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array<int, string>
     */
    public function provides() : array
    {
        return array_keys($this->repositories);
    }
}
