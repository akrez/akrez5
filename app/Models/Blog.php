<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    public $incrementing = false;

    protected $keyType = 'string';

    protected $primaryKey = 'name';

    protected $fillable = ['title', 'slug', 'description'];

    public static function scopeUserCreated($query, $userId)
    {
        return $query->where('created_by', $userId);
    }
}
