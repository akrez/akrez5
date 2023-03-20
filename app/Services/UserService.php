<?php

namespace App\Services;

use App\Models\Blog;
use App\Models\User;

class UserService
{
    public static function setActiveBlog(User $user, Blog $blog)
    {
        $user->active_blog = $blog->name;
        $user->save();
    }
}
