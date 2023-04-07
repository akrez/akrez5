<?php

namespace App\Models;

use App\Enums\ProductStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'code', 'product_status',];

    public function scopeFilterBlogName(Builder $query, $blogName)
    {
        $query->where('blog_name', $blogName);
    }

    public function scopeOrderDefault(Builder $query)
    {
        $query->orderBy('created_at', 'DESC');
    }

    public static function scopeFilterActive($query)
    {
        return $query->where('product_status', ProductStatus::ACTIVE);
    }
}
