<?php

namespace Feature;

use App\Models\{
    User as UserModel,
    Coin as CoinModel,
};
use App\Base\Coin\Manager as CoinManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Pagination\LengthAwarePaginator;
use Mockery;
use Tests\TestCase;

class CryptoPricesFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_crypto_prices_with_snapshots(): void
    {
        $user = UserModel::factory()->create();

        $this->actingAs($user);

        $coin = CoinModel::factory()->create([
            'id' => 'bitcoin',
            'name' => 'Bitcoin',
            'symbol' => 'btc',
        ]);

        $coin->setRelation('snapshots', collect([]));

        $mock = Mockery::mock(CoinManager::class);

        $mock->shouldReceive('lastUpdates')
            ->once()
            ->andReturn(
                new LengthAwarePaginator(
                    collect([$coin]),
                    1, // total
                    50, // per page
                    1   // current page
                )
            );

        $this->app->instance(CoinManager::class, $mock);

        $response = $this->getJson(route('api.v1.prices.get'));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    [
                        'id',
                        'name',
                        'symbol',
                        'snapshots',
                    ]
                ],
                'meta' => [
                    'current_page',
                    'last_page',
                    'per_page',
                    'total',
                ]
            ]);
    }
}