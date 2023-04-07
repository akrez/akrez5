@extends('site')

@section('header', __('Blogs'))

@section('content')
    <div class="row mb-2">
        <div class="col-md-2 pull-right">
            <a class="btn btn-success w-100" href="{{ route('blogs.create') }}">@lang('Create :resource', ['resource' => __('Blog')])</a>
        </div>
    </div>
    <div class="row">
        @foreach ($blogs as $blog)
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h5>
                            <span class="{{ $blog->blog_status == App\Enums\BlogStatus::ACTIVE ? 'text-success' : 'text-danger' }}">{{ $blog->title }}</span>
                            <small class="text-muted">{{ $blog->slug }}</small>
                        </h5>
                        <p class="card-text text-justify">{{ $blog->description }}</p>
                        <div class="row gy-1">
                            <div class="col-md-6">
                                <a href="{{ route('blogs.edit', ['blog' => $blog->name]) }}"
                                    class="btn btn-primary w-100">@lang('Edit')</a>
                            </div>
                            <div class="col-md-6">
                                <form enctype="multipart/form-data" method="post"
                                    action="{{ route('blogs.active', ['blog' => $blog->name]) }}">
                                    @csrf
                                    @method('PATCH')
                                    <input type="submit" class="btn btn-primary w-100" value="@lang('Select')">
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
