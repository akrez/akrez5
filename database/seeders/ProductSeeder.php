<?php

namespace Database\Seeders;

use App\Enums\ProductStatus;
use App\Models\Blog;
use App\Models\Product;
use App\Models\User;
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
        $blog = Blog::query()->where('name', '=', 'shahabtahrir')->first();
        $user = User::query()->where('id', '=', $blog->created_by)->orderBy('id', 'ASC')->first();

        Product::create([
            'title' => 'پایه چسب مدل 3030',
            'code' => '3030',
            'blog_name' => $blog->name,
            'created_by' => $user->id,
            'product_status' => ProductStatus::ACTIVE,
        ]);
    }
}
