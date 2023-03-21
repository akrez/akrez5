<?php

namespace App\Http\Controllers\Gallery;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreGalleryRequest;
use App\Http\Requests\UpdateGalleryRequest;
use App\Models\Gallery;
use App\Services\GalleryService;
use App\Support\UserActiveBlog;
use App\View\Components\AkrezGridTable;
use Illuminate\Support\Facades\Auth;

class BlogLogoController extends Controller
{
    protected function findQuery($model): \Illuminate\Database\Eloquent\Builder
    {
        return Gallery::filterModel(UserActiveBlog::name(), GalleryService::CATEGORY_BLOG_LOGO, $model)
            ->orderBy('seq', 'desc')
            ->orderBy('created_at', 'asc');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): \Illuminate\Contracts\View\View
    {
        $blog = UserActiveBlog::get();

        $galleries = static::findQuery($blog)->get();

        $galleriesGridTable = AkrezGridTable::build($galleries)
            ->newRawColumn('<img src="{{ $src }}" class="img-fluid max-width-32-px">', function ($model) {
                return [
                    'src' => GalleryService::getUrl($model),
                ];
            })
            ->newFieldColumn('name')
            ->newFieldColumn('seq')
            ->newRawColumn('{{ $model->is_main ? __("Yes") : __("No") }}', [], __('validation.attributes.is_main'))
            ->newRawColumn('<a class="btn btn-info text-light w-100" href="{{ $href }}"><i class="fas fa-user"></i>{{ $label }}</a>', function ($model) use ($blog) {
                return [
                    'label' => __('Edit'),
                    'href' => route('blogs.logos.edit', [
                        'blog' => $blog,
                        'logo' => $model,
                    ]),
                ];
            })
            ->newRawColumn('<form enctype="multipart/form-data" action="{{ $action }}" method="POST">
                    @csrf
                    @method("DELETE")
                    <button type="submit" class="btn btn-danger w-100">@lang("Delete")</button>
                </form>', function ($model) use ($blog) {
                return [
                    'action' => route('blogs.logos.destroy', [
                        'blog' => $blog,
                        'logo' => $model,
                    ]),
                ];
            })
            ->render();

        return view('galleries.index', [
            'label' => __('Logos'),
            'subheader' => $blog->title,
            'galleriesGridTable' => $galleriesGridTable,
            'action' => route('blogs.logos.store', [
                'blog' => $blog,
            ]),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(StoreGalleryRequest $request)
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(StoreGalleryRequest $request)
    {
        $blog = UserActiveBlog::get();

        $file = $request->file('image');

        GalleryService::store($request->validated(), $file, UserActiveBlog::name(), GalleryService::CATEGORY_BLOG_LOGO, $blog, Auth::id());

        return redirect()
            ->route('blogs.logos.index', [
                'blog' => $blog,
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
    public function show()
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($blogName, $galleryName)
    {
        $blog = UserActiveBlog::get();

        $gallery = static::findQuery($blog)->findOrFail($galleryName);

        return view('galleries.edit', [
            'label' => __('Logos'),
            'gallery' => $gallery,
            'subheader' => $blog->title.' / '.$gallery->name,
            'action' => route('blogs.logos.update', [
                'blog' => $blog,
                'logo' => $gallery,
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
    public function update(UpdateGalleryRequest $request, $blogName, $galleryName)
    {
        $blog = UserActiveBlog::get();

        $gallery = static::findQuery($blog)->findOrFail($galleryName);

        GalleryService::update($gallery, $request->validated());

        return redirect()
            ->route('blogs.logos.index', [
                'blog' => $blog,
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
    public function destroy($blogName, $galleryName)
    {
        $blog = UserActiveBlog::get();

        $gallery = static::findQuery($blog)->findOrFail($galleryName);

        GalleryService::delete($gallery);

        return redirect()
            ->route('blogs.logos.index', [
                'blog' => $blog,
            ])
            ->with('success', __('The file was deleted!', [
                'resource' => $gallery->name,
            ]));
    }
}
