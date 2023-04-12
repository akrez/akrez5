<?php

namespace App\Enums;

use App\Support\Enum;

class MetaCategory extends Enum
{
    public const CATEGORY_PRODUCT_PROPERTY = 'product_property';
    public const CATEGORY_PRODUCT_CATEGORY = 'product_label';
    public const CATEGORY_BLOG_KEYWORD = 'blog_keyword';

    public static function getItems()
    {
        return [
            static::CATEGORY_PRODUCT_PROPERTY => 'product_property',
            static::CATEGORY_PRODUCT_CATEGORY => 'product_label',
            static::CATEGORY_BLOG_KEYWORD => 'blog_keyword',
        ];
    }
}
