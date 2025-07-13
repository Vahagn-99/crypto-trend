<?php

declare(strict_types=1);

namespace App\Base\Coin\Cache;

use App\Base\Coin\Dto\GetPriceLastUpdatesFilter;
use App\Models\Coin;
use Carbon\Carbon;

class Keys
{
    /**
     * @var string
     */
    private const PREFIX = Coin::class;

    //****************************************************************
    //************************ Ключи *****************************
    //****************************************************************

    /**
     * Получение ключа для получения криптовалюты по ID
     *
     * @param string $id
     * @return string
     */
    public static function find(string $id): string
    {
        return self::makeKey($id);
    }

    /**
     * Получение ключа для получения всех криптовалют по фильтру
     *
     * @param \App\Base\Coin\Dto\GetPriceLastUpdatesFilter $filter
     * @return string
     */
    public static function getLastUpdates(GetPriceLastUpdatesFilter $filter): string
    {
        return self::makeKey($filter->toJson());
    }

    //****************************************************************
    //************************ Теги **********************************
    //****************************************************************

    /**
     * Получение тега для получения всех криптовалют по фильтру
     *
     * @return string
     */
    public static function getLastUpdatesTag(): string
    {
        return self::PREFIX . ':get:last:updates';
    }

    //****************************************************************
    //************************ Support **********************************
    //****************************************************************

    /**
     * Создание ключа кеширования
     *
     * @param string $key
     * @return string
     */
    private static function makeKey(string $key) : string
    {
        return md5($key);
    }
}
