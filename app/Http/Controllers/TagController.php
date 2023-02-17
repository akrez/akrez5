<?php

namespace App\Http\Controllers;

use App\Http\Requests\SyncTagRequest;
use App\Models\Product;
use App\Services\TagService;

class TagController extends Controller
{
    public function productForm(Product $product): \Illuminate\Contracts\View\View
    {
        return view('tags.product_form', [
            'tagsTextareaValue' => TagService::getAsText($product),
            'product' => $product,
        ]);
    }

    public function productSync(SyncTagRequest $request, Product $product)
    {
        TagService::syncModel($request->tagsArray, $product);
        return redirect()->back();
    }
}
