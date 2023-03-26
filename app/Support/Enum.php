<?php

namespace App\Support;

class Enum
{
    public static function getItems()
    {
        return [];
    }

    public static function getKeys()
    {
        return array_keys(static::getItems());
    }

    public static function getValue($item)
    {
        $items = static::getItems();
        if (key_exists($item, $items)) {
            return $items[$item];
        }

        return null;
    }
}
