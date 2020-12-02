<?php

namespace Database\Factories;

use App\Models\TradingCardGame;
use Illuminate\Database\Eloquent\Factories\Factory;

class TradingCardGameFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TradingCardGame::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => 'Game ' . $this->faker->word,
        ];
    }
}
