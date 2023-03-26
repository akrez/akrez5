@extends('site')

@section('header', __('Create :resource', ['resource' => __('Contact')]))

@section('content')
    @include('contacts._form')
@endsection
