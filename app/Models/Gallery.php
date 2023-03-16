<?php

namespace App\Models;

use App\Services\GalleryService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Gallery extends Model
{
    use HasFactory;
    use SoftDeletes;

    const UPDATED_AT = null;

    protected $fillable = ['seq',];

    public function scopeFilterModel($query, $blogname, $model = null, $modelId = null)
    {
        $query->where('blog_name', $blogname);

        $modelClassQuery = GalleryService::extractModelClass($model);
        if (null !== $modelClassQuery) {
            $query->where('model_class', $modelClassQuery);
        }

        $modelIdQuery = GalleryService::extractModelId($modelId, $model);
        if (null !== $modelIdQuery) {
            $query->where('model_id', $modelIdQuery);
        }
    }

    public function scopeFilterName($query, $name = null)
    {
        $query->where('name', $name);
    }
}
