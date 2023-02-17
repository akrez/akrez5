@extends('site')

@section('header', __('Edit :resource', ['resource' => __('Tags')]))
@section('subheader', $product->title)

@section('content')
    <form enctype="multipart/form-data" action="{{ route('products.tags.sync', ['product' => $product->id]) }}" method="POST">
        @csrf
        @include('components/formBuilder', [
            'type' => 'textarea',
            'name' => 'tags',
            'value' => $tagsTextareaValue,
            'label' => __('Tags'),
            'errorsArray' => $errors->get('tags'),
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
