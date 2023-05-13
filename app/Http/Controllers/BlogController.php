<?php

namespace App\Http\Controllers;

use App\Facades\BlogFacade;
use App\Facades\UserFacade;
use App\Http\Requests\StoreBlogRequest;
use App\Models\Blog;
use App\Http\Requests\UpdateBlogRequest;
use App\Models\Visit;
use Illuminate\Support\Facades\Auth;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $blogs = Blog::userCreated($user->id)->orderBy('created_at', 'desc')->paginate(5);
        return view('blogs.index', [
            'blogs' => $blogs,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('blogs.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreBlogRequest  $request
     * @return \Illuminate\Http\Response
     */
    // public function store(HttpRequest $request)
    public function store(StoreBlogRequest $request)
    {
        $blog = new Blog($request->validated());
        $blog->name = $request->name;
        $blog->created_by = Auth::id();
        $blog->save();
        return redirect()
            ->route('blogs.index')
            ->with('success', 'sdfsdgsfhdf dfh dfhdfhdf hdhdf hdfhdfh');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function show(Blog $blog)
    {
        $visits = Visit::filterBlogName($blog->name)
            ->orderDefault()
            ->paginate(250);

        return view('blogs.show', [
            'blog' => $blog,
            'visits' => $visits,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function edit(Blog $blog)
    {
        return view('blogs.edit', [
            'blog' => $blog,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateBlogRequest  $request
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateBlogRequest $request, Blog $blog)
    {
        $blog->update($request->validated());
        $blog->save();
        return redirect()
            ->route('blogs.index')
            ->with('success', 'sdfsdgsfhdf dfh dfhdfhdf hdhdf hdfhdfh');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function destroy(Blog $blog)
    {
        //
    }

    public function active(string $blog_name)
    {
        $user = Auth::user();
        $blog =  Blog::userCreated($user->id)->where('name', $blog_name)->first();
        if ($user and $blog) {
            $user->active_blog = $blog->name;
            $user->save();
        }
        return redirect()->route('blogs.index');
    }
}
