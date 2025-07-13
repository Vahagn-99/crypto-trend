<?php

declare(strict_types=1);

if (! function_exists('alt_log')) {
    function alt_log() : App\Support\AltLog
    {
        return app(App\Support\AltLog::class);
    }
}

if (! function_exists('report_admin')) {
    function report_admin(App\Base\System\Events\Dto\NotifyAdminData $data) : void
    {
        dispatch(new App\Base\System\Events\NotifyAdmin($data));
    }
}