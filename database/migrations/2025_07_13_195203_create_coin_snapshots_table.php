<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('coin_snapshots', function (Blueprint $table) {
            $table->id();

            $table->string('coin_id');
            $table->foreign('coin_id')
                ->references('id')
                ->on('coins')
                ->onDelete('cascade');

            $table->string('vs_currency')->default('usd')->comment('Валюта, в которой получены значения (например, usd, eur, rub)');
            $table->string('used_provider')->default('coingecko')->comment('Используемый провайдер для получения данных');

            $table->decimal('price', 20, 10)->comment('Текущая цена монеты');
            $table->decimal('volume', 30, 0)->comment('Объём торгов за последние 24 часа');
            $table->decimal('market_cap', 30, 0)->comment('Рыночная капитализация монеты');
            $table->decimal('percent_change_24h', 6, 2)->comment('Изменение цены за последние 24 часа в процентах');

            $table->dateTime('fetched_at')->useCurrent()->comment('Время получения данных');

            $table->index(['coin_id', 'fetched_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coin_snapshots');
    }
};
