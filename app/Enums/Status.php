<?php

namespace App\Enums;

use App\Support\Enum;
use App\Support\Helper;

class Status extends Enum
{
    public const DEACTIVE = 0;
    public const ACTIVE = 1;

    public static function getItems()
    {
        return [
            static::DEACTIVE => __('status_deactive'),
            static::ACTIVE => __('status_active'),
        ];
    }

    public static function getStatusItems($statuses)
    {
        return array_intersect_key(static::getItems(), array_flip($statuses));
    }
}
