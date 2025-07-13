<?php

declare(strict_types=1);

namespace App\Base\Coin\Repository;

use App\Models\Coin as CoinModel;
use App\Base\Coin\Dto\GetPriceLastUpdatesFilter;
use Carbon\Carbon;
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

    /**
     * Получение криптовалюты по ID
     *
     * @param string $id
     * @return \App\Models\Coin|null
     */
    public function find(string $id) : ?CoinModel;

    /**
     * Проверка наличия снапшота криптовалюты в указанной валюте на указанную дату
     *
     * @param string $id
     * @param string $vs_currency
     * @param \Carbon\Carbon $fetched_at
     * @return bool
     */
    public function hasSnapshot(string $id, string $vs_currency, Carbon $fetched_at): bool;
}
