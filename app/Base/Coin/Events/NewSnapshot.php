<?php

namespace App\Base\Coin\Events;

use App\Base\Coin\Events\Dto\ListUpdatedData;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewSnapshot
{
    use Dispatchable, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct()
    {
        //
    }
}
