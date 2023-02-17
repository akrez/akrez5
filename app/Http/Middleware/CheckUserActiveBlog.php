<?php

namespace App\Http\Middleware;

use App\Models\Blog;
use App\Support\UserActiveBlog;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserActiveBlog extends SetUserActiveBlog
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $this->set();

        if (!UserActiveBlog::has()) {
            return redirect()->route('blogs.index');
        }

        return $next($request);
    }
}
