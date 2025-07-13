<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Coin extends Model
{
    /** @use HasFactory<\Database\Factories\CoinFactory> */
    use HasFactory;

    /** @var string */
    protected $table = 'coins';

    /** @var string */
    protected $primaryKey = 'id';

    /** @var bool */
    public $incrementing = false;

    /** @var string */

    protected $keyType = 'string';

    /** @var bool */
    public $timestamps = false;

    /*****************************************************************************
    ************************* Связи ******************************************
    ******************************************************************************/

    /**
     *  Получение истории криптовалют
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function snapshots() : HasMany
    {
        return $this->hasMany(CoinSnapshot::class, 'coin_id', 'id');
    }
}
