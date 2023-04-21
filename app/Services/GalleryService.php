<?php

namespace App\Services;

use App\Models\Gallery;
use App\Support\Helper;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class GalleryService
{
    public const CATEGORY_PRODUCT_IMAGE = 'product_image';
    public const CATEGORY_BLOG_LOGO = 'blog_logo';
    public const CATEGORY_BLOG_HERO = 'blog_hero';

    public static function getStorageDisk(): Filesystem
    {
        $diskName = env('FILESYSTEM_DRIVER');

        return Storage::disk($diskName);
    }

    public static function getValidationRules($isStore)
    {
        $imageRules = [
            'image' => [
                'required',
                'image',
                'mimes:jpeg,png,jpg,gif,svg',
                'max:2048',
            ],
        ];

        $commonRules = [
            'seq' => ['nullable', 'numeric'],
            'is_selected' => ['nullable', 'boolean'],
        ];

        if ($isStore) {
            return $imageRules + $commonRules;
        } else {
            return $commonRules;
        }
    }

    public static function update(Gallery $gallery, array $attributes)
    {
        $gallery->fill($attributes);
        $gallery->update();

        static::setSelected($gallery, $attributes['is_selected']);
        static::resetSelected($gallery);
    }

    public static function delete(Gallery $gallery)
    {
        if ($gallery->delete()) {
            $path = static::getUri($gallery->name);
            static::getStorageDisk()->delete($path);
            static::resetSelected($gallery);
        }
    }

    public static function store(array $attributes, $file, $blogName, $category, $model, $userCreatedId)
    {
        $image = Image::make($file);
        $ext = static::getExtensionByMime($image->mime());

        do {
            $name = static::generateImageFileName($ext);
        } while (Gallery::filterName($name)->first());

        $gallery = new Gallery($attributes);
        $gallery->blog_name = $blogName;
        $gallery->model_class = Helper::extractModelClass($model);
        $gallery->model_id = Helper::extractModelId($model);
        $gallery->category = $category;
        $gallery->name = $name;
        $gallery->ext = $ext;
        $gallery->created_by = $userCreatedId;
        $gallery->created_at = Helper::getNowCarbonDate();
        if ($gallery->save()) {
            $path = static::getUri($gallery->name);
            static::getStorageDisk()->put($path, $image->encode());
            static::setSelected($gallery, $attributes['is_selected']);
            static::resetSelected($gallery);
        }
    }

    public static function getExtensionByMime($imagetype): ?string
    {
        switch ($imagetype) {
            case 'image/bmp':
                return 'bmp';
            case 'image/cis-cod':
                return 'cod';
            case 'image/gif':
                return 'gif';
            case 'image/ief':
                return 'ief';
            case 'image/jpeg':
                return 'jpg';
            case 'image/pipeg':
                return 'jfif';
            case 'image/tiff':
                return 'tif';
            case 'image/x-cmu-raster':
                return 'ras';
            case 'image/x-cmx':
                return 'cmx';
            case 'image/x-icon':
                return 'ico';
            case 'image/x-portable-anymap':
                return 'pnm';
            case 'image/x-portable-bitmap':
                return 'pbm';
            case 'image/x-portable-graymap':
                return 'pgm';
            case 'image/x-portable-pixmap':
                return 'ppm';
            case 'image/x-rgb':
                return 'rgb';
            case 'image/x-xbitmap':
                return 'xbm';
            case 'image/x-xpixmap':
                return 'xpm';
            case 'image/x-xwindowdump':
                return 'xwd';
            case 'image/png':
                return 'png';
            case 'image/x-jps':
                return 'jps';
            case 'image/x-freehand':
                return 'fh';
            default:
                return null;
        }
    }

    public static function getUrl($model)
    {
        $url = static::getUri($model->name);

        return static::getStorageDisk()->url($url);
    }

    public static function getUri($name)
    {
        return implode('/', [
            $name,
        ]);
    }

    public static function generateImageFileName($ext)
    {
        return substr(uniqid(rand(), true), 0, 12) . '.' . $ext;
    }

    public static function resetSelected(Gallery $gallery)
    {
        $galleries = Gallery::filterGallery($gallery)
            ->orderDefault()
            ->get();

        $selectedGallery = $galleries->first();

        foreach ($galleries as $gallery) {
            $isSelected = ($selectedGallery and $gallery->name == $selectedGallery->name);
            static::setSelected($gallery, $isSelected);
        }
    }

    private static function setSelected(Gallery $gallery, $isSelected)
    {
        if (boolval($isSelected) != boolval($gallery->selected_at)) {
            $gallery->selected_at = ($isSelected ? Helper::getNowCarbonDate() : null);
            $gallery->save();
        }
    }

    public static function getApiResponse($blogName, $category, $groupByModelId = true): array
    {
        $galleries = Gallery::filterCategory($blogName, $category)
            ->orderDefault()
            ->get();

        $result = [];
        foreach ($galleries as $gallery) {

            $modelId = ($groupByModelId ? $gallery->model_id : null);

            $resultUniqueKey =  $modelId;

            if (!isset($result[$resultUniqueKey])) {
                $result[$resultUniqueKey] = [
                    'model_id' => $modelId,
                    'names' => [],
                ];
            }
            $result[$resultUniqueKey]['names'][] = $gallery->name;
        }

        return array_values($result);
    }
}
