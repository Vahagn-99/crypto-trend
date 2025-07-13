<?php

namespace Database\Factories;

use App\Models\Coin;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CoinSnapshot>
 */
class CoinSnapshotFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
       return [
            'coin_id' => Coin::factory(),
            'price' => $this->faker->randomFloat(2, 0, 1000),
            'volume' => $this->faker->randomFloat(2, 0, 1000),
            'market_cap' => $this->faker->randomFloat(2, 0, 1000),
            'percent_change_24h' => $this->faker->randomFloat(2, 0, 1000),
            'fetched_at' => $this->faker->dateTime(),
       ];
    }
}
