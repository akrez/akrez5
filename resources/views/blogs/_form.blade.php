@section('content')
    <form enctype="multipart/form-data"
        action="{{ isset($blog) ? route('blogs.update', ['blog' => $blog->name]) : route('blogs.store') }}" method="POST">
        @csrf
        @if (isset($blog))
            @method('PUT')
        @endif
        @if (!isset($blog))
            @include('components/formBuilder', [
                'name' => 'name',
                'value' => isset($blog) ? $blog->name : '',
            ])
        @endif
        @include('components/formBuilder', [
            'name' => 'title',
            'value' => isset($blog) ? $blog->title : '',
        ])
        @include('components/formBuilder', [
            'name' => 'slug',
            'value' => isset($blog) ? $blog->slug : '',
        ])
        @include('components/formBuilder', [
            'type' => 'textarea',
            'name' => 'description',
            'value' => isset($blog) ? $blog->description : '',
        ])
        @include('components/formBuilder', [
            'name' => 'blog_status',
            'label' => __('validation.attributes.status'),
            'type' => 'select',
            'value' => isset($blog) ? $blog->blog_status : '',
            'selectOptions' => App\Enums\BlogStatus::getItems(),
        ])
        @include('components/formBuilder', [
            'type' => 'submit',
            'name' => 'submit',
            'value' => isset($blog) ? __('Edit') : __('Create'),
            'size' => 2,
            'label' => '',
            'class' => 'btn btn-primary w-100',
        ])
    </form>
@endsection
