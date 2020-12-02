<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\TradingCardGame;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $trading_card_games = TradingCardGame::all();
        $tcg_ids = [];
        foreach ($trading_card_games as $trading_card_game)
            array_push($tcg_ids, $trading_card_game->id);

        Category::factory()
            ->afterCreating(function (Category $category) use ($tcg_ids) {
                Product::factory()
                    ->count(10)
                    ->state(['category_id' => $category->id])
                    ->state(new Sequence(
                        ['trading_card_game_id' => Arr::random($tcg_ids)],
                        ['trading_card_game_id' => null],
                    ))
                    ->create();
            })
            ->count(5)
            ->create();
    }
}
