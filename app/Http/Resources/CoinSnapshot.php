<?php
declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="CoinSnapshot",
 *     title="CoinSnapshot",
 *     @OA\Property(property="coin_id", type="integer", example="bitcoin"),
 *     @OA\Property(property="price", type="integer", example="15000"),
 *     @OA\Property(property="volume", type="integer", example="15000"),
 *     @OA\Property(property="market_cap", type="integer", example="15000"),
 *     @OA\Property(property="percent_change_24h", type="integer", example="15000"),
 *     @OA\Property(property="fetched_at", type="string", example="2025-07-13 00:00:00"),
 *     @OA\Property(property="used_provider", type="string", example="coingecko"),
 *     @OA\Property(property="vs_currency", type="string", example="usd"),
 * )
 * @property \App\Models\CoinSnapshot resource
 */
class CoinSnapshot extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'coin_id' => $this->resource->coin_id,
            'price' => $this->resource->price,
            'volume' => $this->resource->volume,
            'market_cap' => $this->resource->market_cap,
            'percent_change_24h' => $this->resource->percent_change_24h,
            'fetched_at' => $this->resource->fetched_at,
        ];
    }
}
