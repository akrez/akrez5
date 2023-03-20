<?php

namespace App\Models;

use App\Support\Helper;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Property extends Model
{
    use HasFactory;
    use SoftDeletes;

    public const UPDATED_AT = null;

    public function scopeFilterModel(Builder $query, $blogName, $category, $model)
    {
        $query->where('blog_name', $blogName);
        $query->where('category', $category);
        $query->where('model_class', Helper::extractModelClass($model));
        if (isset($model->id)) {
            $query->where('model_id', Helper::extractModelId($model));
        } else {
            $query->whereNull('model_id');
        }
    }
}
