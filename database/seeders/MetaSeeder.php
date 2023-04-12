<?php

namespace Database\Seeders;

use App\Enums\MetaCategory;
use App\Models\Blog;
use App\Models\Product;
use App\Services\MetaService;
use Illuminate\Database\Seeder;
use Faker\Factory;

class MetaSeeder extends Seeder
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

        MetaService::store(
            [
                [
                    'key' => null,
                    'values' => $faker->words(7),
                ],
            ],
            $blog->name,
            MetaCategory::CATEGORY_PRODUCT_CATEGORY,
            $product,
            $blog->created_by
        );

        MetaService::store([
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
        ], $blog->name, MetaCategory::CATEGORY_PRODUCT_PROPERTY, $product, $blog->created_by);
    }
}
