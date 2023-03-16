<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\Product;
use App\Services\PropertyService;
use App\Services\TagService;
use Faker\Factory;
use Illuminate\Database\Seeder;

class PropertySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $blog = Blog::query()->where('name', '=', 'shahabtahrir')->first();
        $product = Product::query()->where('blog_name', '=', $blog->name)->orderBy('id', 'ASC')->first();

        $faker = Factory::create();

        PropertyService::store([
            [
                'key' => $faker->words(1, true),
                'values' => $faker->words(7),
            ],
            [
                'key' => $faker->words(1, true),
                'values' => $faker->words(7),
            ],
            [
                'key' => $faker->words(1, true),
                'values' => $faker->words(7),
            ],
        ], $blog->name, PropertyService::CATEGORY_PRODUCT, $product, $blog->created_by);
    }
}
