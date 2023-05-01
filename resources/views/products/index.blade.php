@extends('site')

@section('header', __('Products'))
@section('subheader', UserActiveBlog::attr('title'))

@section('content')
    <div class="row">
        <div class="col-md-2 mb-2 pull-right">
            <a class="btn btn-success w-100" href="{{ route('products.create') }}">
                @lang('Create :resource', ['resource' => __('Product')])
            </a>
        </div>
        <div class="col-md-2 mb-2 pull-right">
            <a class="btn btn-success w-100" href="{{ route('products.port') }}">
                @lang('Import :resource', ['resource' => __('Products')])
            </a>
        </div>
        <div class="col-md-2 mb-2 pull-right">
            <a class="btn btn-success w-100" href="{{ route('products.categories.port') }}">
                @lang('Import :resource', ['resource' => __('Categories')])
            </a>
        </div>
        <div class="col-md-2 mb-2 pull-right">
            <a class="btn btn-success w-100" href="{{ route('products.properties.port') }}">
                @lang('Import :resource', ['resource' => __('Properties')])
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
