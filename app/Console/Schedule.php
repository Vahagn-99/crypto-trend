<?php

declare(strict_types=1);

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule as BaseSchedule;

final class Schedule
{
    /**
     * Запуск работ по расписанию.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    public function run(BaseSchedule $schedule) : void
    {
        $this->system($schedule);

        $this->app($schedule);
    }

    /**
     * Запуск системных работ по расписанию.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function system(BaseSchedule $schedule) : void
    {
        $schedule->command('horizon:snapshot')->everyFiveMinutes();

        $schedule->command('sanctum:prune-expired --hours=24')->daily();
    }

    /**
     * Запуск работ приложения по расписанию.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function app(BaseSchedule $schedule) : void
    {
        $schedule->command("sync:crypto")->daily()->at('00:00');
    }
}
