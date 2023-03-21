<form enctype="multipart/form-data" action="{{ $action }}" method="POST">
    @csrf
    @if (isset($gallery))
        @method('PUT')
    @endif
    @include('components/formBuilder', [
        'type' => 'file',
        'name' => 'image',
        'label' => __('validation.attributes.image'),
        'errorsArray' => $errors->get('image'),
        'visible' => $isCreate,
    ])
    @include('components/formBuilder', [
        'name' => 'seq',
        'label' => __('validation.attributes.seq'),
        'errorsArray' => $errors->get('seq'),
        'value' => isset($gallery) ? $gallery->seq : '',
    ])
    @include('components/formBuilder', [
        'type' => 'select',
        'name' => 'is_main',
        'label' => __('validation.attributes.is_main'),
        'errorsArray' => $errors->get('is_main'),
        'value' => isset($gallery) ? $gallery->is_main : '',
        'selectOptions' => [
            '' => __('No'),
            '1' => __('Yes'),
        ],
    ])
    @include('components/formBuilder', [
        'type' => 'submit',
        'name' => 'submit',
        'size' => 2,
        'label' => '',
        'class' => 'btn btn-primary w-100',
        'value' => isset($gallery) ? __('Edit') : __('Upload :resource', ['resource' => __('Gallery')]),
    ])
</form>
