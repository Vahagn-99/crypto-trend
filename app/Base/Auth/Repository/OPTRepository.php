<?php

declare(strict_types=1);


namespace App\Base\Auth\Repository;

use App\Models\PhoneOtp;
use App\Repository\Query;

class OPTRepository implements IOTPRepository
{

    /**
     * OPTRepository constructor.
     *
     * @param \App\Repository\Query $query
     */
    public function __construct(
        private readonly Query $query,
    ) {
        //
    }

    //****************************************************************
    //************************ Получение *****************************
    //****************************************************************

    /**
     * Получение записи по номеру телефона и коду(опционально)
     *
     * @param string $phone
     * @param string|null $code
     * @return \App\Models\PhoneOtp|null
     *
     */
    public function getByPhone(string $phone, ?string $code= null) : ?PhoneOtp
    {
        return $this->query->builder()
            ->where('phone', $phone)
            ->when($code, function ($query) use ($code) {
                $query->where('code', $code);
            })
            ->first();
    }
}
