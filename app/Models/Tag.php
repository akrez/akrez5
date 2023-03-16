<?php

namespace App\Models;

use App\Support\Helper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tag extends Model
{
    use HasFactory;
    use SoftDeletes;

    const UPDATED_AT = null;

    public function scopeFilterModel($query, $blogName, $category, $model)
    {
        $query->where('blog_name', $blogName);
        $query->where('category', $category);
        $query->where('model_class', Helper::extractModelClass($model));
        $query->where('model_id', Helper::extractModelId($model));
    }
}
