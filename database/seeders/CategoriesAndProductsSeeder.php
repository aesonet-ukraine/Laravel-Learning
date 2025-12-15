<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesAndProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        DB::table('categories')->truncate();
        DB::table('products')->truncate();

        Category::factory(2)->create();
        Category::factory(5)->hasProducts(rand(10, 50))->create();

        Category::factory(2)->withParent()->create();
        Category::factory(5)->withParent()->hasProducts(rand(10, 50))->create();
    }
}
