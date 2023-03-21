@extends('site')

@section('header', __('Edit :resource', ['resource' => __('Galleries')]))
@section('subheader', $product->title . ' / ' . $gallery->name)

@section('content')
    @include('galleries.products._form', [
        'gallery' => $gallery,
        'isCreate' => false,
        'action' => route('products.galleries.update', [
            'product' => $product->id,
            'gallery' => $gallery->name,
        ]),
    ])
@endsection
