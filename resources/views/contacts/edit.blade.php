@extends('site')

@section('header', __('Edit :resource', ['resource' => __('Contact')]))
@section('subheader', $contact->title)

@section('content')
    @include('contacts._form', [
        'contact' => $contact,
    ])
@endsection
