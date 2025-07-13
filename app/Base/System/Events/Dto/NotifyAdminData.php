<?php

declare(strict_types=1);

namespace App\Base\System\Events\Dto;

use Illuminate\Support\Carbon;
use Spatie\LaravelData\Data;
use Throwable;

class NotifyAdminData extends Data
{
    /**
     * Constructor NotifyAdminData
     *
     * @param string $error_message
     * @param int $error_code
     * @param string $error_file
     * @param string $error_line
     * @param string $error_exception
     * @param string $error_trace
     * @param \Illuminate\Support\Carbon $event_dispatched_at
     * @param array $additional_data
     */
    public function __construct(
        public string $error_message,
        public int $error_code,
        public string $error_file,
        public string $error_line,
        public string $error_exception,
        public string $error_trace,
        public Carbon $event_dispatched_at,
        public array $additional_data = [],
    ) {
        //
    }

    /**
     *  Создание объекта из объекта исключения
     *
     * @param Throwable $e
     * @param array $additional_data
     * @return NotifyAdminData
     */
    public static function fromException(Throwable $e, array $additional_data = []) : NotifyAdminData
    {
        return self::from([
            'error_message' => $e->getMessage(),
            'error_code' => $e->getCode(),
            'error_file' => $e->getFile(),
            'error_line' => $e->getLine(),
            'error_exception' => get_class($e),
            'error_trace' => $e->getTraceAsString(),
            'event_dispatched_at' => Carbon::now(),
            'additional_data' => $additional_data,
        ]);
    }
}
