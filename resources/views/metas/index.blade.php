@extends('site')

@section('header', __('Edit :resource', ['resource' => $label]))
@section('subheader', $subheader)

@section('content')
    <form enctype="multipart/form-data" action="{{ $action }}" method="POST">
        @csrf
        @include('components/formBuilder', [
            'type' => 'textarea',
            'name' => 'content',
            'value' => $content,
            'label' => $label,
            'errorsArray' => $errors->get('content'),
            'textareaRows' => substr_count($content, "\n") + 2,
        ])
        @include('components/formBuilder', [
            'type' => 'submit',
            'name' => 'submit',
            'value' => __('Edit'),
            'size' => 2,
            'label' => '',
            'class' => 'btn btn-primary w-100',
        ])
    </form>
@endsection
