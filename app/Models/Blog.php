<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use App\Enums\BlogStatus;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    public $incrementing = false;

    protected $keyType = 'string';

    protected $primaryKey = 'name';

    protected $fillable = ['title', 'slug', 'blog_status', 'description'];

    public function scopeFilterName(Builder $query, $blogName)
    {
        $query->where('name', $blogName);
    }

    public static function scopeUserCreated($query, $userId)
    {
        return $query->where('created_by', $userId);
    }

    public static function scopeFilterActive($query)
    {
        return $query->where('blog_status', BlogStatus::ACTIVE);
    }
}
