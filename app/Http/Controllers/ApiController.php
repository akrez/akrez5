<?php

namespace App\Http\Controllers;

use App\Enums\MetaCategory;
use App\Models\Blog;
use App\Models\Contact;
use App\Models\Product;
use App\Services\GalleryService;
use App\Services\MetaService;
use App\Services\VisitService;

class ApiController extends Controller
{
    protected function jsonResponse($blogName, $httpCode = 200, $data = [])
    {
        VisitService::store(
            $blogName,
            $httpCode,
            request()->ip(),
            request()->method(),
            request()->url(),
            request()->userAgent()
        );

        return response()->json(
            $data,
            $httpCode
        );
    }

    public function index($blogName)
    {
        $blog = Blog::filterName($blogName)->filterActive()->first();
        if (!$blog) {
            return $this->json($blogName, 404);
        }

        $products = Product::filterBlogName($blog->name)->filterActive()
            ->orderDefault()
            ->get();

        $contacts = Contact::filter($blog->name)->filterActive()
            ->orderDefault()
            ->get();

        $blogCategories = MetaService::getApiResponse($blog->name, MetaCategory::CATEGORY_PRODUCT_CATEGORY);
        $productsCategories = MetaService::getApiResponse($blog->name, MetaCategory::CATEGORY_PRODUCT_CATEGORY, true);
        $blogKeywords = MetaService::getApiResponse($blog->name, MetaCategory::CATEGORY_BLOG_KEYWORD);
        $productsProperties = MetaService::getApiResponse($blog->name, MetaCategory::CATEGORY_PRODUCT_PROPERTY, true, true);

        $productsImages = GalleryService::getApiResponse($blog->name, GalleryService::CATEGORY_PRODUCT_IMAGE);
        $blogLogos = GalleryService::getApiResponse($blog->name, GalleryService::CATEGORY_BLOG_LOGO);
        $blogHeros = GalleryService::getApiResponse($blog->name, GalleryService::CATEGORY_BLOG_HERO);

        return $this->jsonResponse($blogName, 200, [
            'blog' => $blog,
            'blog_categories' => $blogCategories,
            'products' => $products,
            'products_categories' => $productsCategories,
            'blog_keywords' => $blogKeywords,
            'products_properties' => $productsProperties,
            'products_images' => $productsImages,
            'blog_logos' => $blogLogos,
            'blog_heros' => $blogHeros,
            'contacts' => $contacts,
        ]);
    }
}
