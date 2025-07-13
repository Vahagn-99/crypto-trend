<?php

declare(strict_types=1);

namespace App\Base\Coin\Repository;

use App\Models\Coin as CoinModel;
use App\Base\Coin\Dto\GetPriceLastUpdatesFilter;
use App\Repository\Query;
use Illuminate\Pagination\LengthAwarePaginator;

class CoinRepository implements ICoinRepository
{
    /**
     * CoinRepository constructor.
     *
     * @param \App\Repository\Query $query
     */
    public function __construct(
        private readonly Query $query,
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
    public function find(string $id): ?CoinModel
    {
        return $this->query->builder()->find($id);
    }

    //****************************************************************
    //************************ Получение с джоинами ******************
    //****************************************************************

    /**
     * Получение всех криптовалют в указанной валюте
     *
     * @param \App\Base\Coin\Dto\GetPriceLastUpdatesFilter $filter
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getLastUpdates(GetPriceLastUpdatesFilter $filter) : LengthAwarePaginator
    {
        return $this->query->builder()
            ->with([
                'snapshots' => function ($query) use ($filter) {
                    $query->when(! empty($filter->from), fn($query) => $query->where('fetched_at', '>=', $filter->from));
                    $query->when(! empty($filter->vs_currency), fn($query) => $query->where('vs_currency', $filter->vs_currency));
                    $query->when(! empty($filter->used_provider), fn($query) => $query->where('used_provider', $filter->used_provider));
                    $query->orderBy('fetched_at', 'asc');
                }
            ])
            ->paginate(
                $filter->pagination->per_page,
                $filter->pagination->columns,
                $filter->pagination->page_name,
                $filter->pagination->page,
                $filter->pagination->total
            );
    }

    //****************************************************************
    //************************ Проверки ******************************
    //****************************************************************

    //****************************************************************
    //********************* Работа с билдерами ***********************
    //****************************************************************
}
