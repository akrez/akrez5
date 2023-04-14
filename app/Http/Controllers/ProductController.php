<?php

namespace App\Http\Controllers;

use App\Enums\MetaCategory;
use App\Enums\ProductStatus;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Gallery;
use App\Models\Product;
use App\Services\GalleryService;
use App\Services\MetaService;
use App\Support\UserActiveBlog;
use App\View\Components\AkrezGridTable;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    protected function findQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return Product::filterBlogName(UserActiveBlog::name())
            ->orderDefault();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = $this->findQuery()->paginate(50);
        $productsGridTable = AkrezGridTable::build($products)
            ->newRawColumn('{{ $model->title }}<br>{{ $model->code }}<br>{{ $productStatus }}<br><a class="btn btn-info text-light mt-2" href="{{ $href }}">{{ $label }}</a>',  function ($model) {
                return [
                    'productStatus' => ProductStatus::getValue($model->product_status),
                    'categories' => MetaService::getAsTextWithoutKey(UserActiveBlog::name(), MetaCategory::CATEGORY_PRODUCT_CATEGORY, $model),
                    'href' => route('products.edit', ['product' => $model,]),
                    'label' => __('Edit'),
                ];
            }, __('Product'))
            ->newRawColumn('@foreach ($galleries as $galleryKey => $gallery) <img src="{{ \App\Services\GalleryService::getUrl($gallery) }}" class="img-fluid max-width-32-px"> @endforeach <br> <a class="btn btn-info text-light mt-2" href="{{ $href }}">{{ $label }}</a>',  function ($model) {
                return [
                    'galleries' => Gallery::filterModel(UserActiveBlog::name(), GalleryService::CATEGORY_PRODUCT_IMAGE, $model)->orderDefault()->get(),
                    'href' => route('products.images.index', ['product' => $model,]),
                    'label' => __('Edit'),
                ];
            }, __('Images'))
            ->newRawColumn('<pre>{{ $categories }}</pre><a class="btn btn-info text-light" href="{{ $href }}">{{ $label }}</a>',  function ($model) {
                return [
                    'categories' => MetaService::getAsTextWithoutKey(UserActiveBlog::name(), MetaCategory::CATEGORY_PRODUCT_CATEGORY, $model),
                    'href' => route('products.categories.index', ['product' => $model,]),
                    'label' => __('Edit'),
                ];
            }, __('Categories'))
            ->newRawColumn('<pre>{{ $properties }}</pre><a class="btn btn-info text-light" href="{{ $href }}">{{ $label }}</a>',  function ($model) {
                return [
                    'properties' => MetaService::getAsTextWithKey(UserActiveBlog::name(), MetaCategory::CATEGORY_PRODUCT_PROPERTY, $model),
                    'href' => route('products.properties.index', ['product' => $model,]),
                    'label' => __('Edit'),
                ];
            }, __('Properties'))
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
        $product = new Product($request->validated());
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
    public function show($productId)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit($productId)
    {
        $product = $this->findQuery()->findOrFail($productId);
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
    public function update(UpdateProductRequest $request, $productId)
    {
        $product = $this->findQuery()->findOrFail($productId);
        $product->update($request->validated());
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
    public function destroy($productId)
    {
    }
}
