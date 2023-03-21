@extends('site')

@section('header', __('Edit :resource', ['resource' => $label]))
@section('subheader', $subheader)

@section('content')
    @include('galleries._form', [
        'gallery' => $gallery,
        'isCreate' => false,
        'action' => $action,
    ])
@endsection
