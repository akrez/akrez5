<?php

namespace App\Http\Controllers\Meta;

use App\Enums\MetaCategory;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMetaWithoutKeyRequest;
use App\Models\Product;
use App\Services\MetaService;
use App\Support\UserActiveBlog;
use Illuminate\Support\Facades\Auth;

class ProductCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Product $product): \Illuminate\Contracts\View\View
    {
        return view('metas.index', [
            'label' => __('Categories'),
            'subheader' => $product->title,
            'content' => MetaService::getAsTextWithoutKey(UserActiveBlog::name(), MetaCategory::CATEGORY_PRODUCT_CATEGORY, $product),
            'action' => route('products.categories.store', ['product' => $product->id]),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(StoreMetaWithoutKeyRequest $request, Product $product)
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(StoreMetaWithoutKeyRequest $request, Product $product)
    {
        MetaService::store($request->contentAsArray, UserActiveBlog::name(), MetaCategory::CATEGORY_PRODUCT_CATEGORY, $product, Auth::id());

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function update(StoreMetaWithoutKeyRequest $request, Product $product)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
    }
}
