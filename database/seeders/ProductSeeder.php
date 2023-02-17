<?php

namespace Database\Seeders;

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
        $user = User::query()->orderBy('id', 'ASC')->first();
        $blog = Blog::query()->where('name', '=', 'shahabtahrir')->first();

        Product::create([
            'title' => 'پایه چسب مدل 3030',
            'code' => '3030',
            'blog_name' => $blog->name,
            'created_by' => $user->id,
        ]);
    }
}
