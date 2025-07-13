<?php

declare(strict_types=1);


namespace App\Base\Auth\Dto;

use Spatie\LaravelData\Attributes\Validation\Rule;
use Spatie\LaravelData\Data;

class VerifyData extends Data
{
    /**
     * @param string $phone
     * @param string $code
     */
    public function __construct(
        #[Rule('required', 'exists:users,phone')]
        public readonly string $phone,
        #[Rule('required', 'exists:phone_otps,code')]
        public readonly string $code
    ) {
        //
    }
}
