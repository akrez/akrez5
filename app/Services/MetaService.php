<?php

namespace App\Services;

use App\Models\Meta;
use App\Support\Helper;

class MetaService
{
    const SEPARATOR_LINES = [PHP_EOL];
    const SEPARATOR_KEY_VALUES = [":", "\t"];
    const SEPARATOR_VALUES = [",", "،", "\t"];

    const GLUE_LINES = PHP_EOL;
    const GLUE_KEY_VALUES = ":";
    const GLUE_VALUES = ",";

    public static function parseWithoutKey($content)
    {
        $separators = array_merge(
            MetaService::SEPARATOR_LINES,
            //MetaService::SEPARATOR_KEY_VALUES,
            MetaService::SEPARATOR_VALUES
        );

        $key = null;

        $values = Helper::iexplode($separators, $content);
        $values = Helper::filterArray($values);

        return [
            $key => [
                'key' => $key,
                'values' => $values,
            ],
        ];
    }

    public static function parseWithKey($content)
    {
        $result = [];
        //
        $lines = Helper::iexplode(MetaService::SEPARATOR_LINES, $content);
        $lines = Helper::filterArray($lines);
        //
        foreach ($lines as $lineAsText) {
            $line = Helper::iexplode(MetaService::SEPARATOR_KEY_VALUES, $lineAsText, 2);
            $line = Helper::filterArray($line);
            $line = $line + [0 => '', 1 => ''];
            //
            $key = $line[0];
            if (empty($key)) {
                continue;
            }
            //
            $values = Helper::iexplode(MetaService::SEPARATOR_VALUES, $line[1]);
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

    public static function getAsTextWithoutKey($blogName, $category, $model): String
    {
        $metas = Meta::select('value')
            ->filterModel($blogName, $category, $model)
            ->pluck('value')
            ->all();

        return implode(MetaService::GLUE_LINES, $metas);
    }

    public static function getAsTextWithKey($blogName, $category, $model): String
    {
        $metas = Meta::filterModel($blogName, $category, $model)->get();

        $items = [];
        foreach ($metas as $meta) {
            $items[$meta->key][$meta->value] = $meta->value;
        }

        $result = [];
        foreach ($items as $key => $values) {
            $result[] = $key . MetaService::GLUE_KEY_VALUES . implode(MetaService::GLUE_VALUES, $values);
        }

        return implode(MetaService::GLUE_LINES, $result);
    }

    public static function getApiResponse($blogName, $category): array
    {
        $metas = Meta::filterCategory($blogName, $category)
            ->get();

        $result = [];
        foreach ($metas as $meta) {
            $resultUniqueKey = $meta->model_id . '-' . $meta->key;

            if (!isset($result[$resultUniqueKey])) {
                $result[$resultUniqueKey] = [
                    'key' => $meta->key,
                    'model_id' => $meta->model_id,
                    'values' => [],
                ];
            }
            $result[$resultUniqueKey]['values'][] = $meta->value;
        }

        return array_values($result);
    }

    public static function store(array $keysValues, $blogName, $category, $model, $userCreatedId)
    {
        Meta::filterModel($blogName, $category, $model)->delete();

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
        Meta::insert($insertData);
    }
}
