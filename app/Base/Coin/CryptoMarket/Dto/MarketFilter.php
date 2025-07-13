<?php

declare(strict_types=1);


namespace App\Base\Coin\CryptoMarket\Dto;

use Spatie\LaravelData\Data;

class MarketFilter extends Data
{
    /**
     * MarketFilter constructor.
     *
     * @param string $vs_currency
     * @param string $limit
     */
    public function __construct(
        public readonly string $vs_currency = 'usd',
        public readonly string $limit = '50',
    ) {
        //
    }
}
