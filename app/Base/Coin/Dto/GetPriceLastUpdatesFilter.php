<?php

declare(strict_types=1);

namespace App\Base\Coin\Dto;

use App\Repository\Pagination;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Data;

class GetPriceLastUpdatesFilter extends Data
{
    /**
     * Constructor GetLastUpdatesFilter
     *
     * @param \Carbon\Carbon|null $from
     * @param \Carbon\Carbon|null $to
     * @param string $vs_currency
     * @param string $used_provider
     * @param \App\Repository\Pagination $pagination
     */
    public function __construct(
        #[WithCast(DateTimeInterfaceCast::class, 'Y-m-d')]
        public ?Carbon $from = null,
        #[WithCast(DateTimeInterfaceCast::class, 'Y-m-d')]
        public ?Carbon $to = null,
        public readonly string $vs_currency = 'usd',
        public readonly string $used_provider = 'coingecko',
        public readonly Pagination $pagination = new Pagination(),
    )
    {
        $this->from ??= Carbon::now()->subDay()->startOfDay();

        if (! empty($this->to)) {
            $this->to = $this->to->endOfDay();
        }
    }

    public static function rules() : array
    {
        return [
            'from' => ['string', 'date_format:Y-m-d'],
            'to' => ['string', 'date_format:Y-m-d'],
            'vs_currency' => ['string',  Rule::in(config('coin.available_vs_currencies'))],
            'used_provider' => ['string', 'in:coingecko'],
            'pagination' => ['array'],
        ];
    }
}
