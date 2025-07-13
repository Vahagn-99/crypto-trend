<?php

namespace App\Base\System\Events;

use App\Base\System\Events\Dto\NotifyAdminData;
use Illuminate\Foundation\Events\Dispatchable;

class NotifyAdmin
{
    use Dispatchable;

    /**
     * Create a new event instance.
     */
    public function __construct(public NotifyAdminData $data)
    {
        //
    }
}
