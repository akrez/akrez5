<?php

namespace App\Services;

use App\Models\Product;
use App\Enums\ProductStatus;
use App\Support\Result;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;
use Illuminate\Validation\Rule;

class ProductService
{
    public static function getValidationRules($blogName, ?Product $product = null)
    {
        $codeUniqueRule = Rule::unique('products', 'code')->where(function ($query) use ($blogName) {
            $query->where('blog_name', $blogName);
        });
        if ($product) {
            $codeUniqueRule = $codeUniqueRule->ignore($product->id);
        }

        return [
            'title' => 'required|max:60',
            'product_status' => [Rule::in(ProductStatus::getKeys())],
            'seq' => ['nullable', 'numeric'],
            'code' => [
                'required',
                'max:60',
                $codeUniqueRule,
            ],
        ];
    }

    public static function store($blogName, $createdBy, $attributes): Result
    {
        $status = false;
        $product = new Product();
        $validator = Validator::make($attributes, ProductService::getValidationRules($blogName));

        if (!$validator->fails()) {
            $product->fill($validator->validated());
            $product->created_by = $createdBy;
            $product->blog_name = $blogName;
            $status = $product->save();
        }

        return Result::make($status, [], $product, $validator);
    }

    public static function update($blogName, Product $product, $attributes)
    {
        $status = false;
        $validator = Validator::make($attributes, ProductService::getValidationRules($blogName, $product));

        if (!$validator->fails()) {
            $product->fill($validator->validated());
            $status = $product->save();
        }

        return Result::make($status, [], $product, $validator);
    }

    public static function export($products, $cellsGlue = null, $rowsGlue = null)
    {
        $rows = [];

        $header = [
            __('validation.attributes.id'),
            __('validation.attributes.title'),
            __('validation.attributes.code'),
            __('validation.attributes.seq'),
            __('validation.attributes.product_status'),
        ];

        if (null === $cellsGlue) {
            $rows[] = $header;
        } else {
            $rows[] = implode($cellsGlue, $header);
        }

        foreach ($products as $product) {
            $row = [
                $product->id,
                $product->title,
                $product->code,
                $product->seq,
                $product->product_status,
            ];

            if (null === $cellsGlue) {
                $rows[] = $row;
            } else {
                $rows[] = implode($cellsGlue, $row);
            }
        }

        if (null === $rowsGlue) {
            return $rows;
        } else {
            return implode($rowsGlue, $rows);
        }
    }

    public static function import($blogName, $createdBy, $content)
    {
        $status = true;
        $messages = new MessageBag();
        $data = [];

        foreach ($content as $rowKey => $row) {

            if (0 == $rowKey) {
                continue;
            }

            $attributes = [
                'id' => (mb_strlen($row[0]) ? intval($row[0]) : null),
                'title' => (mb_strlen($row[1]) ? trim($row[1]) : null),
                'code' => (mb_strlen($row[2]) ? trim($row[2]) : null),
                'seq' => (mb_strlen($row[3]) ? floatval($row[3]) : null),
                'product_status' => boolval($row[4]),
            ];

            if (empty($attributes['id'])) {
                $result = ProductService::store($blogName, $createdBy, $attributes);
            } elseif ($product = Product::filterBlogName($blogName)->whereId($attributes['id'])->first()) {
                $result = ProductService::update($blogName, $product, $attributes);
            } else {
                $result = null;
            }

            if ($result) {
                $status = ($status and $result->status);
                foreach ($result->validator->errors()->all() as $errorKey => $error) {
                    $messages->add('port', $rowKey + 1 . ': ' .  $error);
                }
                $data[] = $result;
            }
        }

        return Result::make($status, $messages, null, null, $data);
    }
}
