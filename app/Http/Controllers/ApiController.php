<?php

namespace App\Http\Controllers;

use App\Enums\MetaCategory;
use App\Models\Blog;
use App\Models\Contact;
use App\Models\Product;
use App\Services\GalleryService;
use App\Services\MetaService;

class ApiController extends Controller
{
    public function index($blogName)
    {
        $blog = Blog::filterName($blogName)->filterActive()
            ->firstOrFail();

        $products = Product::filterBlogName($blog->name)->filterActive()
            ->orderDefault()
            ->get();

        $contacts = Contact::filter($blog->name)->filterActive()
            ->orderDefault()
            ->get();

        $productsCategories = MetaService::getApiResponse($blog->name, MetaCategory::CATEGORY_PRODUCT_CATEGORY);
        $blogKeywords = MetaService::getApiResponse($blog->name, MetaCategory::CATEGORY_BLOG_KEYWORD);
        $productsProperties = MetaService::getApiResponse($blog->name, MetaCategory::CATEGORY_PRODUCT_PROPERTY);

        $productsImages = GalleryService::getForApiAsModelArray($blog->name, GalleryService::CATEGORY_PRODUCT_IMAGE);
        $blogLogos = GalleryService::getForApiAsArray($blog->name, GalleryService::CATEGORY_BLOG_LOGO);
        $blogHeros = GalleryService::getForApiAsArray($blog->name, GalleryService::CATEGORY_BLOG_HERO);

        return response()->json([
            'blog' => $blog,
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
