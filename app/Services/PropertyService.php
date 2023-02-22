<?php

namespace App\Services;

use App\Models\Property;
use App\Support\UserActiveBlog;
use Illuminate\Support\Carbon;

class PropertyService
{
    const DEFAULT_LINE_SEPARATOR = PHP_EOL;
    const DEFAULT_KEY_VALUES_SEPARATOR = ':';
    const DEFAULT_VALUES_SEPARATOR = ",";
    const DEFAULT_VALUES_SEPARATORS = [",", "،"];
    const DEFAULT_GLUE = PHP_EOL;

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
        $properties = Property::filterModel($model, $modelId)->get();
        //
        $result = [];
        foreach ($properties as $property) {
            $key = $property['key'];
            if (!isset($result[$key])) {
                $result[$key] = [
                    'key' => $key,
                    'values' => [],
                ];
            }
            $result[$key]['values'][] = $property['value'];
        }
        //
        return array_values($result);
    }

    public static function getAsText($model = null, $modelId = null, String $glue = PropertyService::DEFAULT_GLUE): String
    {
        $items = static::getAsArray($model, $modelId);
        //
        $result = [];
        foreach ($items as $item) {
            $result[] = $item['key'] . static::DEFAULT_KEY_VALUES_SEPARATOR . implode(static::DEFAULT_VALUES_SEPARATOR, $item['values']);
        }
        //
        return implode(static::DEFAULT_LINE_SEPARATOR, $result);
    }

    public static function syncModel(array $keysValues, $model = null, $modelId = null)
    {
        Property::filterModel($model, $modelId)->delete();

        $createdAt = Carbon::now()->format('Y-m-d H:i:s.u');

        $insertData = [];
        foreach ($keysValues as $keyValues) {
            foreach ($keyValues['values'] as $value) {
                $insertData[] = [
                    'created_at' => $createdAt,
                    'key' => $keyValues['key'],
                    'value' => $value,
                    'model_class' => static::extractModelClass($model),
                    'model_id' => static::extractModelId($modelId, $model),
                    'blog_name' => UserActiveBlog::name(),
                ];
            }
        }
        Property::insert($insertData);
    }
}
