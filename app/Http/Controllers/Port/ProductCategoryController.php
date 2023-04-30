<?php

namespace App\Http\Controllers\Port;

use App\Enums\MetaCategory;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\MetaService;
use App\Support\Helper;
use App\Support\UserActiveBlog;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as ReaderXlsx;

class ProductCategoryController extends Controller
{
    public function index()
    {
        return view('port.index', [
            'label' => __('Categories'),
            'subheader' => UserActiveBlog::attr('title'),
            'action' => route('ports.products_categories.import'),
            'href' => route('ports.products_categories.export'),
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
            $reader = new ReaderXlsx();
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
