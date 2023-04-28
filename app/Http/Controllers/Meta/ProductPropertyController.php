<?php

namespace App\Http\Controllers\Meta;

use App\Enums\MetaCategory;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMetaWithKeyRequest;
use App\Models\Product;
use App\Services\MetaService;
use App\Support\UserActiveBlog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductPropertyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Product $product): \Illuminate\Contracts\View\View
    {
        return view('metas.index', [
            'label' => __('Properties'),
            'subheader' => $product->title,
            'content' => MetaService::getAsTextWithKey(UserActiveBlog::name(), MetaCategory::CATEGORY_PRODUCT_PROPERTY, $product),
            'action' => route('products.properties.store', ['product' => $product->id]),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(StoreMetaWithKeyRequest $request, Product $product)
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Product $product)
    {
        $result = MetaService::storeWithKey(
            $request->content,
            UserActiveBlog::name(),
            MetaCategory::CATEGORY_PRODUCT_PROPERTY,
            $product,
            Auth::id()
        );

        if ($result->status) {
            return redirect()
                ->route('products.index')
                ->with('success', __('The :resource was created!', [
                    'resource' => $result->model->title,
                ]));
        } else {
            return back()
                ->withErrors($result->validator)
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(StoreMetaWithKeyRequest $request, Product $product)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
    }
}
