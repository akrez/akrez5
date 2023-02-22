@extends('site')

@section('header', __('Edit :resource', ['resource' => __('Properties')]))
@section('subheader', $product->title)

@section('content')
    <form enctype="multipart/form-data" action="{{ route('products.properties.sync', ['product' => $product->id]) }}" method="POST">
        @csrf
        @include('components/formBuilder', [
            'type' => 'textarea',
            'name' => 'properties',
            'value' => $propertiesTextareaValue,
            'label' => __('Properties'),
            'errorsArray' => $errors->get('properties'),
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
