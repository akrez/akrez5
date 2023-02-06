<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Product;
use App\Support\UserActiveBlog;
use App\View\Components\AkrezGridTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::orderBy('created_at', 'desc')->paginate(50);
        $productsGridTable = AkrezGridTable::build($products)
            ->newFieldColumn('title')
            ->newFieldColumn('code')
            ->newTagColumn('a', __('Update'), function ($model) {
                return [
                    'class' => 'btn btn-info text-light w-100',
                    'href' => route('products.update', [
                        'blog' => UserActiveBlog::name(),
                        'product' => $model,
                    ]),
                ];
            }, '')
            ->newTagColumn('a', __('Update'), function ($model) {
                return [
                    'class' => 'btn btn-info text-light w-100',
                    'href' => route('products.update', [
                        'blog' => UserActiveBlog::name(),
                        'product' => $model,
                    ]),
                ];
            }, '')
            ->render();

        return view('products.index', [
            'blogs' => $products,
            'blogsGridTable' => $productsGridTable
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Blog $blog)
    {
        return view('products.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Blog $blog)
    {
        $product = new Product($request->all());
        $product->blog_name = UserActiveBlog::name();
        $product->created_by = Auth::id();
        $product->save();
        return redirect()
            ->route('products.index', ['blog' => UserActiveBlog::name()])
            ->with('success', 'sdfsdgsfhdf dfh dfhdfhdf hdhdf hdfhdfh');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Blog $blog, Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
    }
}
