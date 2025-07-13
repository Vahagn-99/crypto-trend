<?php

declare(strict_types=1);


namespace App\Base\Coin\Events\Dto;

use Spatie\LaravelData\Data;

class ListUpdatedData extends Data
{
    public function __construct(
        public string $event_dispatched_at,
    ) {
        //
    }
}
