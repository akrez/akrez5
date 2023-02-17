@extends('site')

@section('header', __('Edit :resource', ['resource' => __('Product')]))
@section('subheader', $product->title)

@section('content')
    @include('products._form', [
        'product' => $product,
    ])
@endsection
