<?php

namespace App\Http\Controllers\Tag;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTagRequest;
use App\Services\TagService;
use App\Support\UserActiveBlog;
use Illuminate\Support\Facades\Auth;

class BlogKeywordController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): \Illuminate\Contracts\View\View
    {
        $model = UserActiveBlog::get();

        return view('tags.index', [
            'label' => __('Keywords'),
            'subheader' => $model->title,
            'content' => TagService::getAsText(UserActiveBlog::name(), TagService::CATEGORY_BLOG_KEYWORD, $model),
            'action' => route('keywords.store', ['blog' => $model]),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(StoreTagRequest $request)
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Replasponse
     */
    public function store(StoreTagRequest $request)
    {
        $model = UserActiveBlog::get();

        TagService::store($request->contentAsArray, UserActiveBlog::name(), TagService::CATEGORY_BLOG_KEYWORD, $model, Auth::id());

        return redirect()->back();
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
    public function edit()
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function update(StoreTagRequest $request)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
    }
}
