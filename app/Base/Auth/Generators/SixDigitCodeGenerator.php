<?php

declare(strict_types=1);


namespace App\Base\Auth\Generators;

use Faker\Core\Number;

class SixDigitCodeGenerator implements ICodeGenerator
{
    /**
     * Генерация кода
     *
     * @return string
     */
    public function generate() : string
    {
        return (string)Number::numberBetween(100000, 999999);
    }
}
