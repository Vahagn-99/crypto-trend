<?php
declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="Coin",
 *     title="Coin",
 *     @OA\Property(property="id", type="integer", example=2),
 *     @OA\Property(property="name", type="string", example="Bitcoin"),
 *     @OA\Property(property="symbol", type="string", example="BTC"),
 * )
 * @property \App\Models\Coin resource
 */
class Coin extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'name' => $this->resource->name,
            'symbol' => $this->resource->symbol,
            'snapshots' => CoinSnapshot::collection($this->whenLoaded('snapshots')),
        ];
    }
}
