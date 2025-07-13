<?php

namespace App\Base\Auth\Exceptions;

use App\Exceptions\{
    IReportable,
    ReportAdmin,
};
use Exception;

class OPTException extends Exception implements IReportable
{
    use ReportAdmin;

    /**
     * Исключение, если код не валиден
     *
     * @param string $code
     * @return static
     */
    public static function not_valid(string $code)
    {
        return new static("Код `{$code}` не валиден", 400);
    }

    /**
     * Исключение, если код уже использован
     *
     * @param string $code
     * @return static
     */
    public static function already_used(string $code)
    {
        return new static("Код `{$code}` уже использован", 400);
    }

    /**
     * Исключение, если код истек
     *
     * @param string $code
     * @return static
     */
    public static function expired(string $code)
    {
        return new static("Код `{$code}` истек", 400);
    }
}
