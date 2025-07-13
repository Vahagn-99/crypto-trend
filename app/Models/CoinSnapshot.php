<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CoinSnapshot extends Model
{
    /** @use HasFactory<\Database\Factories\CoinFactory> */
    use HasFactory;

    /** @var string */
    protected $table = 'coin_snapshots';

    /** @var bool */
    public $timestamps = false;

    /*****************************************************************************
     ************************* Связи ******************************************
     ******************************************************************************/

    /**
     *  Получение криптовалюту
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function coin() : BelongsTo
    {
        return $this->belongsTo(Coin::class, 'coin_id', 'id');
    }
}
