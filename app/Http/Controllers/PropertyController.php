<?php

namespace App\Http\Controllers;

use App\Http\Requests\SyncPropertyRequest;
use App\Models\Product;
use App\Services\PropertyService;

class PropertyController extends Controller
{
    public function productForm(Product $product): \Illuminate\Contracts\View\View
    {
        return view('properties.product_form', [
            'propertiesTextareaValue' => PropertyService::getAsText($product),
            'product' => $product,
        ]);
    }

    public function productSync(SyncPropertyRequest $request, Product $product)
    {
        PropertyService::syncModel($request->propertiesArray, $product);
        return redirect()->back();
    }
}
