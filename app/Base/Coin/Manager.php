<?php

declare(strict_types=1);

namespace App\Base\Coin;

use App\Base\Coin\Dto\GetPriceLastUpdatesFilter;
use App\Base\Coin\Repository\ICoinRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class Manager
{
    /**
     * @param \App\Base\Coin\Repository\ICoinRepository $product_repository
     */
    public function __construct(
        private readonly ICoinRepository $product_repository,
    ) {
        //
    }

    /**
     * Получение обновленных данных криптовалют По фильтру
     *
     * @param \App\Base\Coin\Dto\GetPriceLastUpdatesFilter $filter
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function lastUpdates(GetPriceLastUpdatesFilter $filter): LengthAwarePaginator
    {
        return $this->product_repository->getLastUpdates($filter);
    }
}
