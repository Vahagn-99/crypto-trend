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

    /*
    |--------------------------------------------------------------------------
    | Доступные валюты для получения курсов криптовалют
    |--------------------------------------------------------------------------
    */

    'available_vs_currencies' => ["btc", "eth", "ltc", "bch", "bnb", "eos", "xrp", "xlm", "link", "dot", "yfi", "sol", "usd", "aed", "ars", "aud", "bdt", "bhd", "bmd", "brl", "cad", "chf", "clp", "cny", "czk", "dkk", "eur", "gbp", "gel", "hkd", "huf", "idr", "ils", "inr", "jpy", "krw", "kwd", "lkr", "mmk", "mxn", "myr", "ngn", "nok", "nzd", "php", "pkr", "pln", "rub", "sar", "sek", "sgd", "thb", "try", "twd", "uah", "vef", "vnd", "zar", "xdr", "xag", "xau", "bits", "sats"],
];
