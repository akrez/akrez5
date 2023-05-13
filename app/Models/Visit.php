<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    use HasFactory;

    public const UPDATED_AT = null;

    public function scopeFilterBlogName(Builder $query, $blogName)
    {
        $query->where('blog_name', $blogName);
    }

    public function scopeOrderDefault(Builder $query)
    {
        $query->orderBy('created_at', 'DESC');
    }
}
