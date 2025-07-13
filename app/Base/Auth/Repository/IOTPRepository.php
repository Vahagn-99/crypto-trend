<?php

declare(strict_types=1);


namespace App\Base\Auth\Repository;

use App\Models\PhoneOtp;

interface IOTPRepository
{
    /*****************************************************************************
     ************************* Получение ******************************************
     ******************************************************************************/

    /**
     * Получение записи по номеру телефона и коду(опционально)
     *
     * @param string $phone
     * @param string|null $code
     * @return PhoneOtp|null
     */
    public function getByPhone(string $phone, ?string $code = null) : ?PhoneOtp;
}
