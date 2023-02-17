@extends('site')

@section('header', __('Edit :resource', ['resource' => __('Blog')]))
@section('subheader', $blog->name)

@section('content')
    @include('blogs._form', [
        'blog' => $blog,
    ])
@endsection
