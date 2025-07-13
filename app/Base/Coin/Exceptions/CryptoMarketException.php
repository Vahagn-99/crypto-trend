<?php

namespace App\Base\Coin\Exceptions;

use App\Exceptions\{
    IReportable,
    ReportAdmin,
};
use Exception;

class CryptoMarketException extends Exception implements IReportable
{
    use ReportAdmin;

    //
}
