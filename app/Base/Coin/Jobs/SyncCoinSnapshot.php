<?php

namespace App\Base\Coin\Jobs;

use App\Base\Coin\Repository\ICoinRepository;
use Carbon\Carbon;
use App\Models\{
    Coin as CoinModel,
    CoinSnapshot as CoinSnapshotModel,
};
use App\Base\Coin\CryptoMarket\Dto\MarketFilter;
use App\Base\Coin\CryptoMarket\ICryptoMarketManager;
use App\Base\Coin\Events\NewSnapshot as NewSnapshotEvent;
use DB;
use GuzzleHttp\Exception\ConnectException;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Base\Coin\Exceptions\CryptoMarketException;
use Exception;

class SyncCoinSnapshot implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(private readonly MarketFilter $market_filter)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(ICryptoMarketManager $market_manager, ICoinRepository $coin_repository) : void
    {

        try {
            $result = $market_manager->getTopCoins($this->market_filter);
        } catch (CryptoMarketException $exception) {
            alt_log()->file('error_sync_coin_snapshot')->error("Ошибка при получении списка криптовалют из крипто-маркета", [
                    'message' => $exception->getMessage(),
                    'service' => get_class($market_manager),
            ]);

            $this->release(10);
        } catch (ConnectException $exception) {
            alt_log()->file('error_sync_coin_snapshot')->error("Ошибка соединения с крипто-маркетом", [
                'message' => $exception->getMessage(),
                'service' => get_class($market_manager),
            ]);

            $this->release(10);
        } catch (Exception $exception) {
            alt_log()->file('error_sync_coin_snapshot')->error("Ошибка синхронизации криптовалют", [
                'message' => $exception->getMessage(),
            ]);

            $this->fail($exception);
        }

        if (empty($result)) {
            return;
        }
        /** @var \App\Base\Coin\CryptoMarket\Dto\CoinMarketDto $item */
        foreach ($result as $item) {
            DB::beginTransaction();

            try {
                $coin = $coin_repository->find($item->id);

                if (empty($coin)) {
                    $coin = new CoinModel();

                    $coin->id = $item->id;
                    $coin->name = $item->name;
                    $coin->symbol = $item->symbol;

                    $coin->save();
                }

                if ($coin_repository->hasSnapshot($item->id, $this->market_filter->vs_currency, Carbon::now()->subDay())) {
                    continue;
                }

                $snapshot = new CoinSnapshotModel();

                $snapshot->coin_id = $item->id;
                $snapshot->price = $item->price;
                $snapshot->market_cap = $item->market_cap;
                $snapshot->volume = $item->volume;
                $snapshot->percent_change_24h = $item->percent_change_24h;
                $snapshot->fetched_at = Carbon::now();
                $snapshot->vs_currency = $this->market_filter->vs_currency;
                $snapshot->used_provider = config('coin.providers')[get_class($market_manager)];

                $snapshot->save();
                DB::commit();
            } catch (Exception $exception) {
                DB::rollBack();

                alt_log()->file('error_sync_coin_snapshot')->error("Ошибка для монеты {$item->name}", [
                    'message' => $exception->getMessage(),
                ]);
            }
        }

        NewSnapshotEvent::dispatch();
    }
}
