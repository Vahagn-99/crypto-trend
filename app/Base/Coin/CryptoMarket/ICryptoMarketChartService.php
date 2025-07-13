<?php

namespace App\Base\Coin\CryptoMarket;

interface ICryptoMarketChartService
{
    /**
     * Получить график цены для монеты.
     *
     * @param string $coinId
     * @param int $days
     * @param string $currency
     * @return array
     */
    public function getPriceChart(string $coinId, int $days = 10, string $currency = 'usd') : array;
}