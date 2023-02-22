<?php

namespace App\Models;

use App\Services\PropertyService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Property extends Model
{
    use HasFactory;
    use SoftDeletes;

    const UPDATED_AT = null;

    public function scopeFilterModel($query, $model = null, $modelId = null)
    {
        $modelClassQuery = PropertyService::extractModelClass($model);
        if (null !== $modelClassQuery) {
            $query->where('model_class', $modelClassQuery);
        }

        $modelIdQuery = PropertyService::extractModelId($modelId, $model);
        if (null !== $modelIdQuery) {
            $query->where('model_id', $modelIdQuery);
        }
    }
}
