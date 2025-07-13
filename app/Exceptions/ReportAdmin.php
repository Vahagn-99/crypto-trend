<?php

namespace App\Exceptions;

use App\Base\System\Events\Dto\NotifyAdminData;

trait ReportAdmin
{
    /**
     * Уведомить администратора о возникшем исключении
     *
     * @return void
     */
    public function report() : void
    {
        report_admin(NotifyAdminData::fromException($this));
    }
}