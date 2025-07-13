<?php

declare(strict_types=1);


namespace App\Base\Auth\Generators;

class LocalEnvironmentCodeGenerator implements ICodeGenerator
{
    /**
     * Генерация кода
     *
     * @return string
     */
    public function generate() : string
    {
        return config('auth.otp.none_production_env_code');
    }
}
