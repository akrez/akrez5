<?php

namespace App\Models;

use App\Support\Helper;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Gallery extends Model
{
    use HasFactory;
    use SoftDeletes;

    public const UPDATED_AT = null;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $primaryKey = 'name';

    protected $fillable = ['seq'];

    public function scopeFilter(Builder $query, $blogName, $category, $modelClass, $modelId)
    {
        $query->where('blog_name', $blogName);
        $query->where('category', $category);
        $query->where('model_class', $modelClass);
        $query->where('model_id', $modelId);
    }

    public function scopeFilterModel(Builder $query, $blogName, $category, $model)
    {
        $this->scopeFilter(
            $query,
            $blogName,
            $category,
            Helper::extractModelClass($model),
            Helper::extractModelId($model)
        );
    }

    public function scopeFilterGallery(Builder $query, $gallery)
    {
        $this->scopeFilter(
            $query,
            $gallery->blog_name,
            $gallery->category,
            $gallery->model_class,
            $gallery->model_id
        );
    }

    public function scopeFilterName(Builder $query, $name = null)
    {
        $query->where('name', $name);
    }

    public function scopeOrderDefault(Builder $query)
    {
        $query->orderBy('selected_at', 'DESC');
        $query->orderBy('seq', 'DESC');
        $query->orderBy('created_at', 'ASC');
    }
}
