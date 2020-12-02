<?php

namespace Database\Seeders;

use App\Models\TradingCardGame;
use Illuminate\Database\Seeder;

class TradingCardGameSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TradingCardGame::factory()
            ->count(5)
            ->create();
    }
}
