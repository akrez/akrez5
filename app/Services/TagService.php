<?php

namespace App\Services;

use App\Models\Tag;
use App\Support\Helper;

class TagService
{
    public const CATEGORY_PRODUCT_CATEGORY = 'product_label';
    public const CATEGORY_BLOG_KEYWORD = 'blog_keyword';

    public const SEPARATORS = [PHP_EOL, "\t", ',', '،'];

    public const GLUE = "\n";

    public static function parse($content)
    {
        $result = Helper::iexplode(TagService::SEPARATORS, $content);
        $result = Helper::filterArray($result);

        return $result;
    }

    public static function getAsArray($blogName, $category, $model): array
    {
        return Tag::select('value')
            ->filterModel($blogName, $category, $model)
            ->pluck('value')
            ->all();
    }

    public static function getAsText($blogName, $category, $model): string
    {
        return implode(static::GLUE, static::getAsArray($blogName, $category, $model));
    }

    public static function store(array $values, $blogName, $category, $model, $userCreatedId)
    {
        Tag::filterModel($blogName, $category, $model)->delete();

        $createdAt = Helper::getNowCarbonDate();

        $insertData = [];
        foreach ($values as $value) {
            $insertData[] = [
                'created_at' => $createdAt,
                'created_by' => $userCreatedId,
                'value' => $value,
                'model_class' => Helper::extractModelClass($model),
                'model_id' => Helper::extractModelId($model),
                'category' => $category,
                'blog_name' => $blogName,
            ];
        }

        Tag::insert($insertData);
    }
}
