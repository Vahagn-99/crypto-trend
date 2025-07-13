<?php

declare(strict_types=1);

namespace App\Base\Coin\Repository;

use App\Models\Coin as CoinModel;
use App\Base\Coin\Dto\GetPriceLastUpdatesFilter;
use Illuminate\Pagination\LengthAwarePaginator;

interface ICoinRepository
{
    /**
     * Получение всех криптовалют в указанной валюте
     *
     * @param \App\Base\Coin\Dto\GetPriceLastUpdatesFilter $filter
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getLastUpdates(GetPriceLastUpdatesFilter $filter) : LengthAwarePaginator;

    public function find(string $id) : ?CoinModel;
}
