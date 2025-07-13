<?php

use App\Base\Coin\CryptoMarket\CoingeckoCryptoMarketManager;

return [

    /*
    |--------------------------------------------------------------------------
    | Провайдеры для получения информации о криптовалют
    |--------------------------------------------------------------------------
    */
    'providers' => [
        CoingeckoCryptoMarketManager::class => 'coingecko',
    ],
];
