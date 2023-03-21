<?php

namespace App\Services;

use App\Models\Gallery;
use App\Support\Helper;
use App\Support\UserActiveBlog;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class GalleryService
{
    public const CATEGORY_PRODUCT_IMAGE = 'product_image';
    public const CATEGORY_BLOG_LOGO = 'blog_logo';

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
            'is_main' => ['nullable', 'boolean'],
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
    }

    public static function delete(Gallery $gallery)
    {
        if ($gallery->delete()) {
            $path = static::getUri($gallery->name);
            static::getStorageDisk()->delete($path);
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
        $gallery->created_at = Carbon::now()->format('Y-m-d H:i:s.u');
        if ($gallery->save()) {
            $path = static::getUri($gallery->name);
            static::getStorageDisk()->put($path, $image->encode());
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
            'gallery',
            $name,
        ]);
    }

    public static function generateImageFileName($ext)
    {
        return substr(uniqid(rand(), true), 0, 12).'.'.$ext;
    }

    public static function getAsArray($attribute, $model = null, $modelId = null): array
    {
        return Gallery::select($attribute)
            ->filterModel(UserActiveBlog::name(), $model, $modelId)
            ->pluck($attribute)
            ->all();
    }
}
