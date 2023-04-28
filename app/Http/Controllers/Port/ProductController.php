<?php

namespace App\Http\Controllers\Port;

use App\Http\Controllers\Controller;
use App\Services\ProductService;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Support\Helper;
use App\Support\UserActiveBlog;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as ReaderXlsx;

class ProductController extends Controller
{
    protected function findQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return Product::filterBlogName(UserActiveBlog::name())
            ->orderDefault();
    }

    public function index()
    {
        return view('port.index', [
            'label' => __('Products'),
            'subheader' => UserActiveBlog::attr('title'),
            'action' => route('ports.products.import'),
            'href' => route('ports.products.export'),
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
            $reader = new ReaderXlsx();
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
