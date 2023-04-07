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
use Illuminate\Support\Facades\Auth;

class ProductImageController extends Controller
{
    protected function findOrFailProduct($productId): Product
    {
        return Product::filterBlogName(UserActiveBlog::name())
            ->findOrFail($productId);
    }

    protected function findQuery($product): \Illuminate\Database\Eloquent\Builder
    {
        return Gallery::filterModel(UserActiveBlog::name(), GalleryService::CATEGORY_PRODUCT_IMAGE, $product)
            ->orderDefault();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($productId): \Illuminate\Contracts\View\View
    {
        $product = $this->findOrFailProduct($productId);

        $galleries = static::findQuery($product)->get();

        $galleriesGridTable = AkrezGridTable::build($galleries)
            ->newRawColumn('<a href="{{ $src }}" target="_blank"><img src="{{ $src }}" class="img-fluid max-width-32-px"></a>', function ($model) {
                return [
                    'src' => GalleryService::getUrl($model),
                ];
            })
            ->newFieldColumn('name')
            ->newFieldColumn('seq')
            ->newFieldColumn('selected_at')
            ->newRawColumn('<a class="btn btn-info text-light w-100" href="{{ $href }}"><i class="fas fa-user"></i>{{ $label }}</a>', function ($model) use ($product) {
                return [
                    'href' => route('products.images.edit', ['product' => $product, 'image' => $model]),
                    'label' => __('Edit'),
                ];
            })
            ->newRawColumn('<form enctype="multipart/form-data" action="{{ $action }}" method="POST">
                    @csrf
                    @method("DELETE")
                    <button type="submit" class="btn btn-danger w-100">@lang("Delete")</button>
                </form>', function ($model) use ($product) {
                return [
                    'action' => route('products.images.destroy', [
                        'product' => $product,
                        'image' => $model,
                    ]),
                ];
            })
            ->render();

        return view('galleries.index', [
            'label' => __('Images'),
            'subheader' => $product->title,
            'galleriesGridTable' => $galleriesGridTable,
            'action' => route('products.images.store', [
                'product' => $product->id,
            ]),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(StoreGalleryRequest $request, $productId)
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(StoreGalleryRequest $request, $productId)
    {
        $product = $this->findOrFailProduct($productId);

        $file = $request->file('image');

        GalleryService::store($request->validated(), $file, UserActiveBlog::name(), GalleryService::CATEGORY_PRODUCT_IMAGE, $product, Auth::id());

        return redirect()
            ->route('products.images.index', [
                'product' => $product,
            ])
            ->with('success', __('The :resource was created!', [
                'resource' => __('Gallery'),
            ]));
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($productId)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($productId, Gallery $image)
    {
        $product = $this->findOrFailProduct($productId);

        $gallery = static::findQuery($product)->findOrFail($image->name);

        return view('galleries.edit', [
            'label' => __('Images'),
            'gallery' => $gallery,
            'subheader' => $product->title . ' / ' . $gallery->name,
            'action' => route('products.images.update', [
                'product' => $product->id,
                'image' => $gallery->name,
            ]),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateGalleryRequest $request, $productId, Gallery $image)
    {
        $product = $this->findOrFailProduct($productId);

        $gallery = static::findQuery($product)->findOrFail($image->name);

        GalleryService::update($gallery, $request->validated());

        return redirect()
            ->route('products.images.index', [
                'product' => $product,
            ])
            ->with('success', __('The :resource was updated!', [
                'resource' => $gallery->name,
            ]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($productId, Gallery $image)
    {
        $product = $this->findOrFailProduct($productId);

        $gallery = static::findQuery($product)->findOrFail($image->name);

        GalleryService::delete($gallery);

        return redirect()
            ->route('products.images.index', [
                'product' => $product,
            ])
            ->with('success', __('The file was deleted!', [
                'resource' => $gallery->name,
            ]));
    }
}
