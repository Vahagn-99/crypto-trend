<?php

namespace App\Base\Coin\Cache\Listeners;

use App\Base\Coin\Cache\Keys;
use App\Base\Coin\Events\NewSnapshot as NewSnapshotEvent;
use Cache;
use Illuminate\Events\Dispatcher;

class ClearCache
{
    /**
     * Очистка кэша при обновлении списка
     *
     * @param \App\Base\Coin\Events\NewSnapshot $event
     * @return void
     */
    public function handleNewSnapshot(NewSnapshotEvent $event): void
    {
        $this->clearLastUpdatesCache();
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @return array<string, string>
     */
    public function subscribe(Dispatcher $events): array
    {
        return [
            NewSnapshotEvent::class => "handleNewSnapshot",
        ];
    }

    //****************************************************************
    //************************** Support *****************************
    //****************************************************************

    /**
     * Очистка кэша при обновлении списка криптовалют
     *
     * @return void
     */
    private function clearLastUpdatesCache(): void
    {
        Cache::tags(Keys::getLastUpdatesTag())->flush();
    }
}
