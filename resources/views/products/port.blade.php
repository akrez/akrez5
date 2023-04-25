@extends('site')

@section('header', __('Import :resource', ['resource' => $label]))
@section('subheader', $subheader)

@section('content')
    <div class="row">
        <div class="col-md-2 mt-2">
            <div class="form-group">
                <a class="btn btn-primary w-100" href="{{ @route('products.export') }}">
                    @lang('Export :resource', ['resource' => __('Products')])
                </a>
            </div>
        </div>
    </div>
    <div class="row">
        <form enctype="multipart/form-data" action="{{ $action }}" method="POST" class="mb-1">
            @csrf
            @include('components/formBuilder', [
                'type' => 'file',
                'name' => 'port',
                'label' => __('Choose File'),
            ])
            @include('components/formBuilder', [
                'type' => 'submit',
                'name' => 'submit',
                'value' => __('Import :resource', ['resource' => __('Products')]),
                'size' => 2,
                'label' => '',
                'class' => 'btn btn-primary w-100',
            ])
        </form>
    </div>
@endsection
