<?php

namespace App\Http\Controllers\Tag;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTagRequest;
use App\Models\Product;
use App\Services\TagService;
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
        return view('tags.index', [
            'label' => __('Categories'),
            'subheader' => $product->title,
            'content' => TagService::getAsText(UserActiveBlog::name(), TagService::CATEGORY_PRODUCT_CATEGORY, $product),
            'action' => route('products.categories.store', ['product' => $product->id]),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(StoreTagRequest $request, Product $product)
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTagRequest $request, Product $product)
    {
        TagService::store($request->contentAsArray, UserActiveBlog::name(), TagService::CATEGORY_PRODUCT_CATEGORY, $product, Auth::id());

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
    public function update(StoreTagRequest $request, Product $product)
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
