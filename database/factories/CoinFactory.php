<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Coin>
 */
class CoinFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $data = [
            [
                'id' => 'bts_id',
                'name' => 'Bitcoin',
                'symbol' => 'BTC',
            ],
            [
                'id' => 'eth_id',
                'name' => 'Ethereum',
                'symbol' => 'ETH',
            ],
            [
                'id' => 'ltc_id',
                'name' => 'Tether',
                'symbol' => 'USDT',
            ],
            [
                'id' => 'usdc_id',
                'name' => 'USD Coin',
                'symbol' => 'USDC',
            ],
            [
                'id' => 'xrp_id',
                'name' => 'BNB',
                'symbol' => 'BNB',
            ]
        ];

        return Arr::random($data);
    }
}
