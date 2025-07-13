<?php

declare(strict_types=1);


namespace App\Base\Coin\CryptoMarket\Dto;

use Carbon\Carbon;
use Spatie\LaravelData\Data;

class CoinMarketDto extends Data
{
    /**
     * CoinMarketDto constructor.
     *
     * @param string $id
     * @param string $symbol
     * @param string $name
     * @param string $image
     * @param float $price
     * @param float $market_cap
     * @param float $volume
     * @param float $percent_change_24h
     * @param \Carbon\Carbon $last_updated
     */
    public function __construct(
        public readonly string $id,
        public readonly string $symbol,
        public readonly string $name,
        public readonly string $image,
        public readonly float $price,
        public readonly float $market_cap,
        public readonly float $volume,
        public readonly float $percent_change_24h,
        public readonly Carbon $last_updated,
    ) {
        //
    }

    public static function fromApi(array $data): self
    {
        return new self(
            id: $data['id'],
            symbol: $data['symbol'],
            name: $data['name'],
            image: $data['image'],
            price: (float) $data['current_price'],
            market_cap: (float) $data['market_cap'],
            volume: (float) $data['total_volume'],
            percent_change_24h: (float) ($data['price_change_percentage_24h'] ?? 0),
            last_updated: Carbon::parse($data['last_updated']),
        );
    }
}
