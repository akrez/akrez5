@extends('site')

@section('POS_HEAD')
    <style>
        .max-width-height {
            max-width: 32px;
            max-height: 32px;
        }
    </style>
@endsection

@section('header', __('Edit :resource', ['resource' => __('Galleries')]))
@section('subheader', $product->title)

@section('content')
    @include('galleries.product._form', [
        'isCreate' => true,
        'action' => route('products.galleries.store', [
            'product' => $product->id,
        ]),
    ])
    <div class="row mt-4">
        <div class="col-md-12">
            {!! $galleriesGridTable !!}
        </div>
    </div>
@endsection
