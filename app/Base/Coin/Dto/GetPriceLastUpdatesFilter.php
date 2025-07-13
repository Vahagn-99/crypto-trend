<?php

declare(strict_types=1);

namespace App\Base\Coin\Dto;

use App\Repository\Pagination;
use Carbon\Carbon;
use Spatie\LaravelData\Attributes\Validation\Rule;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Data;

class GetPriceLastUpdatesFilter extends Data
{
    /**
     * Constructor GetLastUpdatesFilter
     *
     * @param \Carbon\Carbon|null $from
     * @param string $vs_currency
     * @param string $used_provider
     * @param \App\Repository\Pagination $pagination
     */
    public function __construct(
        #[Rule('string', 'date_format:Y-m-d')]
        #[WithCast(DateTimeInterfaceCast::class, 'Y-m-d')]
        public readonly ?Carbon $from = null,
        #[Rule('string', 'in:usd')]
        public readonly string $vs_currency = 'usd',
        #[Rule('string', 'in:coingecko')]
        public readonly string $used_provider = 'coingecko',
        public readonly Pagination $pagination = new Pagination(),
    ) {
        //
    }
}
