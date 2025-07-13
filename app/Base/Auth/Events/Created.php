<?php

namespace App\Base\Auth\Events;

use App\Models\PhoneOtp as PhoneOtpModel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class Created
{
    use Dispatchable, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public readonly PhoneOtpModel $phoneOtp)
    {
        //
    }
}
