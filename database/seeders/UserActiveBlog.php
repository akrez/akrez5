<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Database\Seeder;

class UserActiveBlog extends Seeder
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

        UserService::setActiveBlog($user, $blog);
    }
}
