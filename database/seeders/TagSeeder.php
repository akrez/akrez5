<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\Product;
use App\Services\TagService;
use Faker\Factory;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
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

        TagService::store(
            $faker->words(7),
            $blog->name,
            TagService::CATEGORY_PRODUCT_TAG,
            $product,
            $blog->created_by
        );
    }
}
