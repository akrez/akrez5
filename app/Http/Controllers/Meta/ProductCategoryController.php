<?php

namespace App\Http\Controllers\Meta;

use App\Enums\MetaCategory;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMetaWithoutKeyRequest;
use App\Models\Product;
use App\Services\MetaService;
use App\Support\Helper;
use App\Support\UserActiveBlog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

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
    public function store(Request $request, Product $product)
    {
        $result = MetaService::storeWithoutKey(
            $request->content,
            UserActiveBlog::name(),
            MetaCategory::CATEGORY_PRODUCT_CATEGORY,
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

    public function port()
    {
        return view('port.index', [
            'label' => __('Categories'),
            'subheader' => UserActiveBlog::attr('title'),
            'action' => route('products.categories.import'),
            'href' => route('products.categories.export'),
        ]);
    }

    public function export()
    {
        $source = MetaService::exportProduct(UserActiveBlog::name(), MetaCategory::CATEGORY_PRODUCT_CATEGORY);
        $sheetName = MetaCategory::CATEGORY_PRODUCT_CATEGORY;
        $fileName =  UserActiveBlog::name() . '-' . $sheetName . '-' . date('Y-m-d-H-i-s') . '.xlsx';
        return Helper::exportExcelSheet($fileName, $sheetName, $source);
    }

    public function import(Request $request)
    {
        $sheetName = MetaCategory::CATEGORY_PRODUCT_CATEGORY;

        $blogName = UserActiveBlog::name();
        $createdBy = Auth::id();

        $port = $request->file('port');

        if ($port and $path = $port->getRealPath()) {
            $reader = new Xlsx();
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($path);
            $sheet = $spreadsheet->getSheetByName($sheetName);
            $content = $sheet->toArray();
            $result = MetaService::importProduct($content, $blogName, $sheetName, $createdBy, false);
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
