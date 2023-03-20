<?php

namespace App\Support;

class Helper
{
    public static function iexplode($delimiters, $string, $limit = PHP_INT_MAX)
    {
        if (!is_array($delimiters)) {
            $delimiters = [$delimiters];
        }
        $delimiter = reset($delimiters);

        $result = [];
        $maskedString = $string;
        while (count($result) + 1 < $limit) {
            $c = 1;
            $maskedStringExploded = str_replace($delimiters, $delimiter, $maskedString, $c);
            $maskedStringExploded = explode($delimiter, $maskedStringExploded, 2);
            if (2 == count($maskedStringExploded)) {
                $result[] = $maskedStringExploded[0];
                $maskedString = $maskedStringExploded[1];
            } else {
                $maskedString = $maskedStringExploded[0];
                break;
            }
        }
        $result[] = $maskedString;

        return $result;
    }

    public static function filterArray($array, $doFilter = true, $checkUnique = true, $doTrim = true): array
    {
        if ($doTrim) {
            $array = array_map('trim', $array);
        }
        if ($checkUnique) {
            $array = array_unique($array);
        }
        if ($doFilter) {
            $array = array_filter($array, 'strlen');
        }

        return $array;
    }

    public static function templatedArray($template = [], $values = [], $const = [])
    {
        return $const + array_intersect_key($values, $template) + $template;
    }

    public static function extractModelClass($model = null): string
    {
        if (null !== $model) {
            return get_class($model);
        }
    }

    public static function extractModelId($model = null)
    {
        if (null !== $model and isset($model->id)) {
            return $model->id;
        }
    }
}
