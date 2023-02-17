@extends('site')

@section('header', __('Create :resource', ['resource' => __('Blog')]))

@section('content')
    @include('blogs._form')
@endsection
