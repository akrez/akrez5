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
        'name' => 'is_selected',
        'label' => __('validation.attributes.is_selected'),
        'errorsArray' => $errors->get('is_selected'),
        'value' => isset($gallery) ? ($gallery->selected_at ? '1' : '') : '',
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
