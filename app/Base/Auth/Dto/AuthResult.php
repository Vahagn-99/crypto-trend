<?php

declare(strict_types=1);


namespace App\Base\Auth\Dto;

use Spatie\LaravelData\Attributes\Validation\Rule;
use Spatie\LaravelData\Data;

class AuthResult extends Data
{
    /**
     * @param string $access_token
     * @param string $type
     * @param string|null $expires_in
     */
    public function __construct(
        public readonly string $access_token,
        public readonly string $type = 'Bearer',
        public readonly ?string $expires_in = null,
    ) {
        //
    }
}
