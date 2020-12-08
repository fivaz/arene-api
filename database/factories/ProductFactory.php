<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => 'Card ' . $this->faker->word,
            'price' => $this->faker->randomFloat(2, 0, 99),
            'stock' => $this->faker->randomNumber(3),
            'minimum_stock' => $this->faker->randomNumber(2),
            //TODO check if it's really necessary to add these fields
            'category_id' => null,
            'trading_card_game_id' => null,
            'language_id' => null,
        ];
    }
}
