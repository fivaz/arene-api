<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::factory()
            ->afterCreating(function (Category $category) {
                Product::factory()
                    ->count(10)
                    ->state(['category_id' => $category->id])
                    ->create();
            })
            ->count(5)
            ->create();
    }
}
