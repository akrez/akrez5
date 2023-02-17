@extends('site')

@section('header', __('Products'))
@section('subheader', UserActiveBlog::attr('title'))

@section('content')
    <div class="row mb-2">
        <div class="col-md-2 pull-right">
            <a class="btn btn-success w-100" href="{{ route('products.create') }}">
                @lang('Create :resource', ['resource' => __('Product')])
            </a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            {!! $productsGridTable !!}
        </div>
    </div>
    {{ $products->links() }}
@endsection
