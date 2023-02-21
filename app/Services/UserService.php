<?php

namespace App\Services;

use App\Models\Blog;
use App\Models\Tag;
use App\Models\User;
use App\Support\UserActiveBlog;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;

class UserService
{
    public static function setActiveBlog(User $user, Blog $blog)
    {
        $user->active_blog = $blog->name;
        $user->save();
    }
}
