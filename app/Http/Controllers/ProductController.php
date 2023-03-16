<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
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
            ->newRawColumn('<a class="btn btn-info text-light w-100" href="{{ $href }}"><i class="fas fa-user"></i>{{ $label }}</a>',  function ($model) {
                return [
                    'href' => route('products.galleries.index', ['product' => $model,]),
                    'label' => __('Galleries'),
                ];
            })
            ->newRawColumn('<a class="btn btn-info text-light w-100" href="{{ $href }}"><i class="fas fa-user"></i>{{ $label }}</a>',  function ($model) {
                return [
                    'href' => route('products.tags.index', ['product' => $model,]),
                    'label' => __('Tags'),
                ];
            })
            ->newRawColumn('<a class="btn btn-info text-light w-100" href="{{ $href }}"><i class="fas fa-user"></i>{{ $label }}</a>',  function ($model) {
                return [
                    'href' => route('products.properties.index', ['product' => $model,]),
                    'label' => __('Properties'),
                ];
            })
            ->newRawColumn('<a class="btn btn-info text-light w-100" href="{{ $href }}"><i class="fas fa-user"></i>{{ $label }}</a>',  function ($model) {
                return [
                    'href' => route('products.edit', ['product' => $model,]),
                    'label' => __('Update'),
                ];
            })
            ->render();

        return view('products.index', [
            'products' => $products,
            'productsGridTable' => $productsGridTable
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('products.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProductRequest $request)
    {
        $product = new Product($request->all());
        $product->blog_name = UserActiveBlog::name();
        $product->created_by = Auth::id();
        $product->save();
        return redirect()
            ->route('products.index', ['blog' => UserActiveBlog::name()])
            ->with('success', __('The :resource was created!', [
                'resource' => $product->title,
            ]));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
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
        return view('products.edit', [
            'product' => $product,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        $product->update($request->all());
        $product->blog_name = UserActiveBlog::name();
        $product->created_by = Auth::id();
        $product->save();
        return redirect()
            ->route('products.index', ['blog' => UserActiveBlog::name()])
            ->with('success', __('The :resource was updated!', [
                'resource' => $product->title,
            ]));
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

    public function active(Product $product)
    {
        $product->is_active = !$product->is_active;

        if ($product->save()) {
            $httpStatus = 200;
        } else {
            $httpStatus = 500;
        }

        return response()->json([], $httpStatus);
    }
}
