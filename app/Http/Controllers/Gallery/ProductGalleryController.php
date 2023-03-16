<?php

namespace App\Http\Controllers\Gallery;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreGalleryRequest;
use App\Http\Requests\UpdateGalleryRequest;
use App\Models\Gallery;
use App\Models\Product;
use App\Services\GalleryService;
use App\Support\UserActiveBlog;
use App\View\Components\AkrezGridTable;

class ProductGalleryController extends Controller
{
    protected function findQuery(Product $product): \Illuminate\Database\Eloquent\Builder
    {
        return Gallery::filterModel(UserActiveBlog::name(), $product)
            ->orderBy('seq', 'desc')
            ->orderBy('created_at', 'asc');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Product $product): \Illuminate\Contracts\View\View
    {
        $galleries = static::findQuery($product)->get();

        $galleriesGridTable = AkrezGridTable::build($galleries)
            ->newRawColumn('<img src="{{ $src }}" class="img-fluid max-width-height">',  function ($model) {
                return [
                    'src' => GalleryService::getUrl($model),
                ];
            })
            ->newFieldColumn('name')
            ->newFieldColumn('seq')
            ->newRawColumn('<a class="btn btn-info text-light w-100" href="{{ $href }}"><i class="fas fa-user"></i>{{ $label }}</a>',  function ($model) use ($product) {
                return [
                    'href' => route('products.galleries.edit', ['product' => $product, 'gallery' => $model,]),
                    'label' => __('Edit'),
                ];
            })
            ->newRawColumn('<form enctype="multipart/form-data" action="{{ $action }}" method="POST">
                    @csrf
                    @method("DELETE")
                    <button type="submit" class="btn btn-danger w-100">@lang("Delete")</button>
                </form>',  function ($model) use ($product) {
                return [
                    'action' => route('products.galleries.destroy', [
                        'product' => $product,
                        'gallery' => $model,
                    ]),
                ];
            })
            ->render();

        return view('galleries.products.index', [
            'product' => $product,
            'galleries' => $galleries,
            'galleriesGridTable' => $galleriesGridTable,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(StoreGalleryRequest $request, Product $product)
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreGalleryRequest $request, Product $product)
    {
        $file = $request->file('image');

        GalleryService::store(GalleryService::CATEGORY_PRODUCT, $request, $file, $product);

        return redirect()
            ->route('products.galleries.index', [
                'product' => $product,
            ])
            ->with('success', __('The :resource was created!', [
                'resource' => __('Gallery'),
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
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product, Gallery $gallery)
    {
        $gallery = static::findQuery($product)->findOrFail($gallery->id);

        return view('galleries.products.edit', [
            'product' => $product,
            'gallery' => $gallery,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateGalleryRequest $request, Product $product, Gallery $gallery)
    {
        $gallery = static::findQuery($product)->findOrFail($gallery->id);

        GalleryService::update($gallery, $request->validated());

        return redirect()
            ->route('products.galleries.index', [
                'product' => $product,
            ])
            ->with('success', __('The :resource was updated!', [
                'resource' => $gallery->name,
            ]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product, Gallery $gallery)
    {
        $gallery = static::findQuery($product)->findOrFail($gallery->id);

        GalleryService::delete($gallery);

        return redirect()
            ->route('products.galleries.index', [
                'product' => $product,
            ])
            ->with('success', __('The file was deleted!', [
                'resource' => $gallery->name,
            ]));
    }
}
