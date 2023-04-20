<?php

namespace App\Services;

use App\Models\Product;
use App\Enums\ProductStatus;
use App\Support\Result;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ProductService
{
    public static function getValidationRules($isStore)
    {
        return [
            'title' => 'required|max:60',
            'code' => 'required|max:60',
            'product_status' => [Rule::in(ProductStatus::getKeys())],
            'seq' => ['nullable', 'numeric'],
        ];
    }

    public static function store($blogName, $createdBy, $data): Result
    {
        $status = false;
        $product = new Product();
        $validator = Validator::make($data, ProductService::getValidationRules(true));

        if (!$validator->fails()) {
            $product->fill($validator->validated());
            $product->created_by = $createdBy;
            $product->blog_name = $blogName;
            $status = $product->save();
        }

        return Result::make($status, [], $product, $validator);
    }

    public static function update(Product $product, $data)
    {
        $status = false;
        $validator = Validator::make($data, ProductService::getValidationRules(false));

        if (!$validator->fails()) {
            $product->fill($validator->validated());
            $status = $product->save();
        }

        return Result::make($status, [], $product, $validator);
    }
}
