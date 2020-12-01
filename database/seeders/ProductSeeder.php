<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //this function can only be used to create products connected to the same Category
        Product::factory()
            ->count(10)
            ->for(Category::factory())
            ->create();
    }
}
