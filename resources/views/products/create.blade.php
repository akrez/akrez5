@extends('site')

@section('header', __('Create :resource', ['resource' => __('Product')]))

@section('content')
    @include('products._form')
@endsection
