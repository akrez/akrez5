@extends('site')

@section('header', __('Edit :resource', ['resource' => $label]))
@section('subheader', $subheader)

@section('content')
    @include('galleries._form', [
        'isCreate' => true,
        'action' => $action,
    ])
    <div class="row mt-4">
        <div class="col-md-12">
            {!! $galleriesGridTable !!}
        </div>
    </div>
@endsection
