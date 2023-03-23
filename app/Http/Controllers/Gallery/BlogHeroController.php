<?php

namespace App\Http\Controllers\Gallery;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreGalleryRequest;
use App\Http\Requests\UpdateGalleryRequest;
use App\Models\Blog;
use App\Models\Gallery;
use App\Services\GalleryService;
use App\Support\UserActiveBlog;
use App\View\Components\AkrezGridTable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\View\View;

class BlogHeroController extends Controller
{
    protected function findQuery($model): \Illuminate\Database\Eloquent\Builder
    {
        return Gallery::filterModel(UserActiveBlog::name(), GalleryService::CATEGORY_BLOG_HERO, $model)
            ->orderDefault();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Blog $blog): View
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
            ->newFieldColumn('selected_at')
            ->newRawColumn('<a class="btn btn-info text-light w-100" href="{{ $href }}"><i class="fas fa-user"></i>{{ $label }}</a>', function ($model) use ($blog) {
                return [
                    'label' => __('Edit'),
                    'href' => route('blogs.heroes.edit', [
                        'blog' => $blog,
                        'hero' => $model,
                    ]),
                ];
            })
            ->newRawColumn('<form enctype="multipart/form-data" action="{{ $action }}" method="POST">
                    @csrf
                    @method("DELETE")
                    <button type="submit" class="btn btn-danger w-100">@lang("Delete")</button>
                </form>', function ($model) use ($blog) {
                return [
                    'action' => route('blogs.heroes.destroy', [
                        'blog' => $blog,
                        'hero' => $model,
                    ]),
                ];
            })
            ->render();

        return view('galleries.index', [
            'label' => __('Heroes'),
            'subheader' => $blog->title,
            'galleriesGridTable' => $galleriesGridTable,
            'action' => route('blogs.heroes.store', [
                'blog' => $blog,
            ]),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(StoreGalleryRequest $request, Blog $blog)
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(StoreGalleryRequest $request, Blog $blog)
    {
        $blog = UserActiveBlog::get();

        $file = $request->file('image');

        GalleryService::store($request->validated(), $file, UserActiveBlog::name(), GalleryService::CATEGORY_BLOG_HERO, $blog, Auth::id());

        return redirect()
            ->route('blogs.heroes.index', [
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
    public function show(Blog $blog, Gallery $hero)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Blog $blog, Gallery $hero)
    {
        $blog = UserActiveBlog::get();

        $gallery = static::findQuery($blog)->findOrFail($hero->name);

        return view('galleries.edit', [
            'label' => __('Heroes'),
            'gallery' => $gallery,
            'subheader' => $blog->title.' / '.$gallery->name,
            'action' => route('blogs.heroes.update', [
                'blog' => $blog,
                'hero' => $gallery,
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
    public function update(UpdateGalleryRequest $request, Blog $blog, Gallery $hero)
    {
        $blog = UserActiveBlog::get();

        $gallery = static::findQuery($blog)->findOrFail($hero->name);

        GalleryService::update($gallery, $request->validated());

        return redirect()
            ->route('blogs.heroes.index', [
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
    public function destroy(Blog $blog, Gallery $hero)
    {
        $blog = UserActiveBlog::get();

        $gallery = static::findQuery($blog)->findOrFail($hero->name);

        GalleryService::delete($gallery);

        return redirect()
            ->route('blogs.heroes.index', [
                'blog' => $blog,
            ])
            ->with('success', __('The file was deleted!', [
                'resource' => $gallery->name,
            ]));
    }
}
