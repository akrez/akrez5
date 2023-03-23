<?php

namespace App\Services;

use App\Models\Property;
use App\Support\Helper;

class PropertyService
{
    const CATEGORY_PRODUCT_PROPERTY = 'product_property';

    const SEPARATOR_LINES = [PHP_EOL];
    const SEPARATOR_KEY_VALUES = [":", "\t"];
    const SEPARATOR_VALUES = [",", "،", "\t"];

    const GLUE_LINES = PHP_EOL;
    const GLUE_KEY_VALUES = ":";
    const GLUE_VALUES = ",";

    public static function parse($content)
    {
        $result = [];
        //
        $lines = Helper::iexplode(PropertyService::SEPARATOR_LINES, $content);
        $lines = Helper::filterArray($lines);
        //
        foreach ($lines as $lineAsText) {
            $line = Helper::iexplode(PropertyService::SEPARATOR_KEY_VALUES, $lineAsText, 2);
            $line = Helper::filterArray($line);
            $line = $line + [0 => '', 1 => ''];
            //
            $key = $line[0];
            if (empty($key)) {
                continue;
            }
            //
            $values = Helper::iexplode(PropertyService::SEPARATOR_VALUES, $line[1]);
            $values = Helper::filterArray($values);
            if (empty($values)) {
                continue;
            }
            //
            if (isset($result[$key]['values'])) {
                $values = array_merge($result[$key]['values'], $values);
                $values = Helper::filterArray($values);
            }
            $result[$key] = [
                'key' => $key,
                'values' => $values,
            ];
        }
        return $result;
    }

    public static function getAsArray($blogName, $category, $model): array
    {
        $properties = Property::filterModel($blogName, $category, $model)->get();
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

    public static function getAsText($blogName, $category, $model): String
    {
        $items = PropertyService::getAsArray($blogName, $category, $model);
        //
        $result = [];
        foreach ($items as $item) {
            $result[] = $item['key'] . PropertyService::GLUE_KEY_VALUES . implode(PropertyService::GLUE_VALUES, $item['values']);
        }
        //
        return implode(PropertyService::GLUE_LINES, $result);
    }

    public static function store(array $keysValues, $blogName, $category, $model, $userCreatedId)
    {
        Property::filterModel($blogName, $category, $model)->delete();

        $createdAt = Helper::getNowCarbonDate();

        $insertData = [];
        foreach ($keysValues as $keyValues) {
            foreach ($keyValues['values'] as $value) {
                $insertData[] = [
                    'created_at' => $createdAt,
                    'created_by' => $userCreatedId,
                    'key' => $keyValues['key'],
                    'value' => $value,
                    'model_class' => Helper::extractModelClass($model),
                    'model_id' => Helper::extractModelId($model),
                    'category' => $category,
                    'blog_name' => $blogName,
                ];
            }
        }
        Property::insert($insertData);
    }
}
