<?php

namespace App\Http\Controllers\Meta;

use App\Enums\MetaCategory;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMetaWithoutKeyRequest;
use App\Services\MetaService;
use App\Support\UserActiveBlog;
use Illuminate\Http\Request;
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

        return view('metas.index', [
            'label' => __('Keywords'),
            'subheader' => $model->title,
            'content' => MetaService::getAsTextWithoutKey(UserActiveBlog::name(), MetaCategory::CATEGORY_BLOG_KEYWORD, $model),
            'action' => route('keywords.store', ['blog' => $model]),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(StoreMetaWithoutKeyRequest $request)
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Replasponse
     */
    public function store(Request $request)
    {
        $result = MetaService::storeWithoutKey(
            $request->content,
            UserActiveBlog::name(),
            MetaCategory::CATEGORY_BLOG_KEYWORD,
            UserActiveBlog::get(),
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
    public function update(StoreMetaWithoutKeyRequest $request)
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
