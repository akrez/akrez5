<?php

namespace App\Models;

use App\Enums\ContactStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = ['contact_type', 'title', 'content', 'link', 'seq', 'contact_status'];

    public function scopeFilter(Builder $query, $blogName)
    {
        $query->where('blog_name', $blogName);
    }

    public function scopeOrderDefault(Builder $query)
    {
        $query->orderBy('seq', 'DESC');
        $query->orderBy('created_at', 'ASC');
    }

    public static function scopeFilterActive($query)
    {
        return $query->where('contact_status', ContactStatus::ACTIVE);
    }
}
