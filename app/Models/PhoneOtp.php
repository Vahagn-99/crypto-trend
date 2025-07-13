<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PhoneOtp extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'phone_otps';

    protected $primaryKey = 'phone';

    /*****************************************************************************
     ************************* Связи ******************************************
     ******************************************************************************/

    /**
     *  Получение пользователя
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class, 'phone', 'phone');
    }
}
