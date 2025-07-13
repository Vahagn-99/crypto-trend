<?php

declare(strict_types=1);

namespace App\Base\Coin\Repository;

use App\Models\Coin as CoinModel;
use App\Base\Coin\Cache\Keys;
use App\Base\Coin\Dto\GetPriceLastUpdatesFilter;
use Cache;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Декоратор кэша при работе с репозиторием криптовалют
 */
class CacheDecorator implements ICoinRepository
{
    private const GET_LAST_UPDATES_TTL = 60;

    /**
     * CachedProductRepository constructor.
     * @param \App\Base\Coin\Repository\CoinRepository $actual
     */
    public function __construct(
        private readonly CoinRepository $actual,
    ) {
       //
    }

    //****************************************************************
    //************************ Получение *****************************
    //****************************************************************

    /**
     * Получение криптовалюты по ID
     *
     * @param string $id
     * @return \App\Models\Coin|null
     */
    public function find(string $id) : ?CoinModel
    {
        return Cache::remember(Keys::find($id), self::GET_LAST_UPDATES_TTL, function () use ($id) {
            return $this->actual->find($id);
        });
    }

    //****************************************************************
    //************************ Получение с джоинами ******************
    //****************************************************************

    /**
     * Получение криптовалют с снапшотами из кэша
     *
     * @param \App\Base\Coin\Dto\GetPriceLastUpdatesFilter $filter
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getLastUpdates(GetPriceLastUpdatesFilter $filter) : LengthAwarePaginator
    {
        return Cache::tags(Keys::getLastUpdatesTag())->remember(Keys::getLastUpdates($filter), self::GET_LAST_UPDATES_TTL, function () use ($filter) {
            return $this->actual->getLastUpdates($filter);
        });
    }

    //****************************************************************
    //************************ Проверки ******************************
    //****************************************************************

    //****************************************************************
    //********************* Работа с билдерами ***********************
    //****************************************************************
}
