<?php

declare(strict_types=1);


namespace App\Base\Auth\Generators;

interface ICodeGenerator
{
    /**
     * Генерация кода
     *
     * @return string
     */
    public function generate() : string;
}
