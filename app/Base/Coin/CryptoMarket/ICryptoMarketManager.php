<?php

namespace App\Base\Coin\CryptoMarket;

use App\Base\Coin\CryptoMarket\Dto\MarketFilter;
use Spatie\LaravelData\DataCollection;

interface ICryptoMarketManager
{
    /**
     * Получить топ криптовалют.
     *
     * @param \App\Base\Coin\CryptoMarket\Dto\MarketFilter $filter
     * @return \Spatie\LaravelData\DataCollection
     *
     * @throws \App\Base\Coin\Exceptions\CryptoMarketException
     * @throws \Illuminate\Http\Client\ConnectionException
     */
    public function getTopCoins(MarketFilter $filter) : DataCollection;
}