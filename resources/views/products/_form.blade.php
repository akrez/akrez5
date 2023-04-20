@section('content')
    <form enctype="multipart/form-data"
        action="{{ isset($product) ? route('products.update', ['product' => $product->id]) : route('products.store') }}"
        method="POST">
        @csrf
        @if (isset($product))
            @method('PUT')
        @endif
        @include('components/formBuilder', [
            'name' => 'title',
            'value' => isset($product) ? $product->title : '',
        ])
        @include('components/formBuilder', [
            'name' => 'code',
            'value' => isset($product) ? $product->code : '',
        ])
        @include('components/formBuilder', [
            'name' => 'seq',
            'value' => isset($product) ? $product->seq : '',
        ])
        @include('components/formBuilder', [
            'name' => 'product_status',
            'label' => __('validation.attributes.status'),
            'type' => 'select',
            'value' => isset($product) ? $product->product_status : '',
            'selectOptions' => App\Enums\ProductStatus::getItems(),
        ])
        @include('components/formBuilder', [
            'type' => 'submit',
            'name' => 'submit',
            'value' => isset($product) ? __('Edit') : __('Create'),
            'size' => 2,
            'label' => '',
            'class' => 'btn btn-primary w-100',
        ])
    </form>
@endsection
