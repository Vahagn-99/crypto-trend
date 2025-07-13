<?php

namespace App\Console\Commands;

use App\Base\Coin\CryptoMarket\Dto\MarketFilter;
use App\Base\Coin\Jobs\SyncCoinSnapshot as SyncCoinSnapshotJob;
use Illuminate\Console\Command;

class SyncPrices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:crypto {--currency=usd}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Синхронизировать топ-50 криптовалют и сохранить снимки';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $currency = $this->option('currency');

        if (! in_array($currency, config('coin.available_vs_currencies'))) {
            $this->error("Недопустимая валюта: $currency");
            $this->newLine();
            $this->info("Доступные валюты: " . implode(', ', config('coin.available_vs_currencies')));

            return 1;
        }

        $this->info("Получение топ-50 монет в валюте: $currency");

        SyncCoinSnapshotJob::dispatch(MarketFilter::from([
            'vs_currency' => $currency,
            'limit' => '50'
        ]));

        $this->info('Успешная синхронизация!');

        return 0;
    }
}
