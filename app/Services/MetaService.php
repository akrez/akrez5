<?php

namespace App\Services;

use App\Models\Meta;
use App\Models\Product;
use App\Support\Helper;
use App\Support\Result;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;

class MetaService
{
    const MAX_LENGTH = 60;

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

    public static function getAsStringWithKey($blogName, $category, $model): array
    {
        $metas = Meta::filterModel($blogName, $category, $model)->get();

        $items = [];
        foreach ($metas as $meta) {
            $items[$meta->key][$meta->value] = $meta->value;
        }

        $result = [];
        foreach ($items as $key => $values) {
            $result[$key] = implode(MetaService::GLUE_KEY_VALUES, $values);
        }

        return $result;
    }

    public static function getAsTextWithKey($blogName, $category, $model): String
    {
        $items = static::getAsStringWithKey($blogName, $category, $model);

        return implode(MetaService::GLUE_LINES, $items);
    }

    public static function getApiResponse($blogName, $category, $groupByModelId = false, $groupByKey = false): array
    {
        $metas = Meta::filterCategory($blogName, $category)
            ->get();

        $result = [];
        foreach ($metas as $meta) {
            $modelId = ($groupByModelId ? $meta->model_id : null);
            $key = ($groupByKey ? $meta->key : null);

            $resultUniqueKey = $modelId . '-' . $key;

            if (!isset($result[$resultUniqueKey])) {
                $result[$resultUniqueKey] = [
                    'key' => $key,
                    'model_id' => $modelId,
                    'values' => [],
                ];
            }

            if (!in_array($meta->value, $result[$resultUniqueKey]['values'])) {
                $result[$resultUniqueKey]['values'][] = $meta->value;
            }
        }

        return array_values($result);
    }

    public static function exportProduct($blogName, $category, $groupByKey = false): array
    {
        $metas = Meta::with('product')
            ->filterCategory($blogName, $category)
            ->get();

        $rows = [];

        $rows[] = [
            __('validation.attributes.code'),
            __('validation.attributes.title'),
            __('validation.attributes.key'),
            __('validation.attributes.value'),
        ];

        foreach ($metas as $meta) {
            $modelId = $meta->model_id;
            $key = ($groupByKey ? $meta->key : null);

            $uniqueKey = $modelId . '-' . $key;

            if (!isset($rows[$uniqueKey])) {
                $rows[$uniqueKey] = [
                    'code' => ($meta->product ? $meta->product->code : null),
                    'title' => ($meta->product ? $meta->product->title : null),
                    'key' => $key,
                    'values' => $meta->value,
                ];
            } else {
                $rows[$uniqueKey]['values'] = ($rows[$uniqueKey]['values'] . MetaService::GLUE_VALUES . $meta->value);
            }
        }

        return $rows;
    }

    public static function importProduct($excelAsArray, $blogName, $category, $userCreatedId, $withKey)
    {
        $contentRows = [];
        foreach ($excelAsArray as $excelRowKey => $excelRow) {
            if (0 == $excelRowKey) {
                continue;
            }

            $attributes = [
                'code' => (mb_strlen($excelRow[0]) ? trim($excelRow[0]) : null),
                'title' => (mb_strlen($excelRow[1]) ? trim($excelRow[1]) : null),
                'key' => (mb_strlen($excelRow[2]) ? trim($excelRow[2]) : null),
                'values' => (mb_strlen($excelRow[3]) ? trim($excelRow[3]) : null),
            ];

            $productCode = $attributes['code'];
            $key = ($withKey ? $attributes['key'] : null);

            if (isset($contentRows[$productCode][$key])) {
                $contentRows[$productCode][$key] = ($contentRows[$productCode][$key] . MetaService::GLUE_VALUES . $attributes['values']);
            } else {
                $contentRows[$productCode][$key] = $attributes['values'];
            }
        }

        $status = true;
        $messages = new MessageBag();
        $data = [];

        foreach ($contentRows as $productCode => $keysValues) {
            if (
                $keysValues and
                $productCode and
                $product = Product::filterBlogName($blogName)->whereCode($productCode)->first()
            ) {
                if ($withKey) {
                    $content = [];
                    foreach ($keysValues as $key => $values) {
                        $content[] = ($key . MetaService::GLUE_KEY_VALUES . $values);
                    }
                    $content = implode(MetaService::GLUE_LINES, $content);
                } else {
                    $content = implode(MetaService::GLUE_LINES, $keysValues);
                }

                if ($withKey) {
                    $result = MetaService::storeWithKey($content, $blogName, $category, $product, $userCreatedId);
                } else {
                    $result = MetaService::storeWithoutKey($content, $blogName, $category, $product, $userCreatedId);
                }

                $status = ($status and $result->status);
                foreach ($result->validator->errors()->all() as $errorKey => $error) {
                    $messages->add('port', $productCode . ': ' .  $error);
                }
                $data[] = $result;
            }
        }


        return Result::make($status, $messages, null, null, $data);
    }

    public static function storeWithKey($content, $blogName, $category, $model, $userCreatedId)
    {
        $keysValues = MetaService::parseWithKey($content);
        $validator = Validator::make(
            [
                'content' => $keysValues,
            ],
            [
                'content.*.key' => 'max:' . static::MAX_LENGTH,
                'content.*.values.*' => 'max:' . static::MAX_LENGTH,
            ],
            [],
            [
                'content.*.key' => __('Key'),
                'content.*.values.*' => __('Value'),
            ],
        );
        //
        return MetaService::store($keysValues, $validator, $blogName, $category, $model, $userCreatedId);
    }

    public static function storeWithoutKey($content, $blogName, $category, $model, $userCreatedId)
    {
        $keysValues = MetaService::parseWithoutKey($content);
        $validator = Validator::make(
            [
                'content' => $keysValues,
            ],
            [
                'content.*.key' => 'max:' . static::MAX_LENGTH,
                'content.*.values.*' => 'max:' . static::MAX_LENGTH,
            ],
            [],
            [
                'content.*.key' => __('Key'),
                'content.*.values.*' => __('Value'),
            ],
        );
        return MetaService::store($keysValues, $validator, $blogName, $category, $model, $userCreatedId);
    }

    private static function store(array $keysValues, $validator, $blogName, $category, $model, $userCreatedId)
    {
        $status = false;
        $insertedData = [];
        //
        if (!$validator->fails()) {
            Meta::filterModel($blogName, $category, $model)->delete();
            $createdAt = Helper::getNowCarbonDate();
            foreach ($keysValues as $keyValues) {
                foreach ($keyValues['values'] as $value) {
                    $insertedData[] = [
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
            $status = Meta::insert($insertedData);
        }
        //
        return Result::make($status, [], $model, $validator, [
            'keysValues' => $keysValues,
            'insertedData' => $insertedData,
        ]);
    }
}
