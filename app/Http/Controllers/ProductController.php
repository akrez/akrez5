<?php

namespace App\Http\Controllers;

use App\Enums\MetaCategory;
use App\Enums\ProductStatus;
use App\Services\ProductService;
use Illuminate\Http\Request;
use App\Models\Gallery;
use App\Models\Product;
use App\Services\GalleryService;
use App\Services\MetaService;
use App\Support\Helper;
use App\Support\UserActiveBlog;
use App\View\Components\AkrezGridTable;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

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
            ->newRawColumn('@foreach ($galleries as $galleryKey => $gallery) <img src="{{ \App\Services\GalleryService::getUrl($gallery) }}" class="img-fluid max-width-32-px"> @endforeach <div class="clearfix"></div> <a class="btn btn-info text-light mb-2 mt-2" href="{{ $href }}">{{ $label }}</a>',  function ($model) {
                return [
                    'galleries' => Gallery::filterModel(UserActiveBlog::name(), GalleryService::CATEGORY_PRODUCT_IMAGE, $model)->orderDefault()->get(),
                    'href' => route('products.images.index', ['product' => $model,]),
                    'label' => __('Edit'),
                ];
            }, __('Images'))
            ->newRawColumn('<div class="h6"> {{ $model->title }} </div>
            <div><small class="text-muted">@lang("validation.attributes.code")</small> {{ $model->code }} </div>
            <div><small class="text-muted">@lang("validation.attributes.seq")</small> {{ $model->seq }} </div>
            <div><small class="text-muted">@lang("validation.attributes.product_status")</small> {{ $productStatus }} </div>
            <a class="btn btn-info text-light mb-2 mt-2" href="{{ $href }}">{{ $label }}</a>',  function ($model) {
                return [
                    'productStatus' => ProductStatus::getValue($model->product_status),
                    'categories' => MetaService::getAsTextWithoutKey(UserActiveBlog::name(), MetaCategory::CATEGORY_PRODUCT_CATEGORY, $model),
                    'href' => route('products.edit', ['product' => $model,]),
                    'label' => __('Edit'),
                ];
            }, __('Product'))
            ->newRawColumn('@foreach (explode("\n", $categories) as $category) {{ $category }} <div class="clearfix"></div> @endforeach <a class="btn btn-info text-light mb-2 mt-2" href="{{ $href }}">{{ $label }}</a>',  function ($model) {
                return [
                    'categories' => MetaService::getAsTextWithoutKey(UserActiveBlog::name(), MetaCategory::CATEGORY_PRODUCT_CATEGORY, $model),
                    'href' => route('products.categories.index', ['product' => $model,]),
                    'label' => __('Edit'),
                ];
            }, __('Categories'))
            ->newRawColumn('@foreach ($properties as $propertiesKey => $propertiesValues) <div><small class="text-muted">{{ $propertiesKey }}</small> {{ $propertiesValues }} </div> @endforeach <div class="clearfix"></div> <a class="btn btn-info text-light mb-2 mt-2" href="{{ $href }}">{{ $label }}</a>',  function ($model) {
                return [
                    'properties' => MetaService::getAsStringWithKey(UserActiveBlog::name(), MetaCategory::CATEGORY_PRODUCT_PROPERTY, $model),
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
    public function store(Request $request)
    {
        $result = ProductService::store(UserActiveBlog::name(), Auth::id(), $request->all());
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
    public function update(Request $request, $productId)
    {
        $product = $this->findQuery()->findOrFail($productId);
        $result = ProductService::update(UserActiveBlog::name(), $product, $request->all());
        if ($result->status) {
            return redirect()
                ->route('products.index')
                ->with('success', __('The :resource was updated!', [
                    'resource' => $product->title,
                ]));
        } else {
            return back()
                ->withErrors($result->validator)
                ->withInput();
        }
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

    public function port()
    {
        return view('port.index', [
            'label' => __('Products'),
            'subheader' => UserActiveBlog::attr('title'),
            'action' => route('products.import'),
            'href' => route('products.export'),
        ]);
    }

    public function export()
    {
        $products = $this->findQuery()->get();
        $source = ProductService::export($products);
        $sheetName = 'products';
        $fileName =  UserActiveBlog::name() . '-' . $sheetName . '-' . date('Y-m-d-H-i-s') . '.xlsx';
        return Helper::exportExcelSheet($fileName, $sheetName, $source);
    }

    public function import(Request $request)
    {
        $blogName = UserActiveBlog::name();
        $createdBy = Auth::id();

        $port = $request->file('port');

        if ($port and $path = $port->getRealPath()) {
            $reader = new Xlsx();
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($path);
            $sheet = $spreadsheet->getSheetByName('products');
            $content = $sheet->toArray();
            $result = ProductService::import($blogName, $createdBy, $content);
        } else {
            $result = null;
        }

        $response = back()->withInput();
        if ($result) {
            $response->withErrors($result->messages);
        }
        return $response;
    }
}
