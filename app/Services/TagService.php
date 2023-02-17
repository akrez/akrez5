<?php

namespace App\Services;

use App\Models\Tag;
use App\Support\UserActiveBlog;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;

class TagService
{
    const DEFAULT_GLUE = PHP_EOL;
    const DEFAULT_SEPARATORS = [PHP_EOL, ",", "،"];

    public static function extractModelClass($model = null): string
    {
        if (null !== $model) {
            return get_class($model);
        }
    }

    public static function extractModelId($modelId = null, $model = null)
    {
        if (0 < strlen($modelId)) {
            return $modelId;
        } elseif (null !== $model and isset($model->id)) {
            return $model->id;
        }
    }

    public static function getAsArray($model = null, $modelId = null): array
    {
        return Tag::select('name')
            ->filterModel($model, $modelId)
            ->pluck('name')
            ->all();
    }

    public static function getAsText($model = null, $modelId = null, String $glue = TagService::DEFAULT_GLUE): String
    {
        return implode($glue, static::getAsArray($model, $modelId));
    }

    public static function syncModel(array $names, $model = null, $modelId = null)
    {
        Tag::filterModel($model, $modelId)->delete();

        $createdAt = Carbon::now()->format('Y-m-d H:i:s.u');

        $insertData = [];
        foreach ($names as $name) {
            $insertData[] = [
                'name' => $name,
                'model_class' => static::extractModelClass($model),
                'model_id' => static::extractModelId($modelId, $model),
                'created_at' => $createdAt,
                'blog_name' => UserActiveBlog::name(),
            ];
        }
        Tag::insert($insertData);
    }
}
