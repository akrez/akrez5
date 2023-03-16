@extends('site')

@section('header', __('Edit :resource', ['resource' => __('Properties')]))
@section('subheader', $product->title)

@section('content')
    <form enctype="multipart/form-data" action="{{ route('products.properties.store', ['product' => $product->id]) }}" method="POST">
        @csrf
        @include('components/formBuilder', [
            'type' => 'textarea',
            'name' => 'content',
            'value' => $content,
            'label' => __('Properties'),
            'errorsArray' => $errors->get('content'),
        ])
        @include('components/formBuilder', [
            'type' => 'submit',
            'name' => 'submit',
            'value' => __('Edit'),
            'size' => 2,
            'label' => '',
            'class' => 'btn btn-primary w-100',
        ])
    </form>
@endsection
