<?php

namespace Database\Seeders;

use App\Models\Coin;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run() : void
    {
        $user = User::factory()->create([
            'name' => 'Test User',
            'phone' => '79991234567',
        ]);
    }
}
