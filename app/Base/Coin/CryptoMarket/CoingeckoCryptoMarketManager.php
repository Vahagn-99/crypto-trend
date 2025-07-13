<?php

namespace App\Base\Coin\CryptoMarket;

use App\Base\Coin\CryptoMarket\Dto\CoinMarketDto;
use App\Base\Coin\CryptoMarket\Dto\MarketFilter;
use App\Base\Coin\Exceptions\CryptoMarketException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Spatie\LaravelData\DataCollection;

class CoingeckoCryptoMarketManager implements ICryptoMarketManager
{
    /** @var string */
    protected static string $base_url = 'https://api.coingecko.com/api/v3';

    /**
     * CoingeckoCryptoMarketManager constructor.
     *
     * @param \Illuminate\Http\Client\PendingRequest $http_client
     */
    public function __construct(
        private readonly PendingRequest $http_client,
    ) {
        $this->http_client->baseUrl(static::$base_url);
    }

    /**
     * Получить топ криптовалют.
     *
     * @param \App\Base\Coin\CryptoMarket\Dto\MarketFilter $filter
     * @return \Spatie\LaravelData\DataCollection
     *
     * @throws \App\Base\Coin\Exceptions\CryptoMarketException
     * @throws \Illuminate\Http\Client\ConnectionException
     */
    public function getTopCoins(MarketFilter $filter) : DataCollection
    {
        $response = $this->http_client->get("/coins/markets", [
            'vs_currency' => $filter->vs_currency,
            'order' => 'market_cap_desc',
            'per_page' => $filter->limit,
            'page' => 1,
            'sparkline' => false,
            'price_change_percentage' => '24h',
        ]);

        if ($response->failed()) {
            throw new CryptoMarketException($response->body(), $response->getStatusCode());
        }

        return new DataCollection(CoinMarketDto::class, collect($response->json())
            ->map(fn(array $coin) => CoinMarketDto::fromApi($coin)));
    }
}