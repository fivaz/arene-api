<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Language;
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
        //TODO possible refactoring using attach or associate
        $trading_card_games = TradingCardGame::all();
        $languages = Language::all();
        $tcg_ids = [];
        $language_ids = [];
        foreach ($trading_card_games as $trading_card_game)
            array_push($tcg_ids, $trading_card_game->id);
        foreach ($languages as $language)
            array_push($language_ids, $language->id);

        Category::factory()
            ->afterCreating(function (Category $category) use ($tcg_ids, $language_ids) {
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
