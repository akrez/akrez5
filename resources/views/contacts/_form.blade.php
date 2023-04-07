@section('content')
    <form enctype="multipart/form-data"
        action="{{ isset($contact) ? route('contacts.update', ['contact' => $contact->id]) : route('contacts.store') }}"
        method="POST">
        @csrf
        @if (isset($contact))
            @method('PUT')
        @endif
        @include('components/formBuilder', [
            'name' => 'contact_type',
            'type' => 'select',
            'value' => isset($contact) ? $contact->contact_type : '',
            'selectOptions' => ['' => ''] + App\Enums\ContactType::getItems(),
        ])
        @include('components/formBuilder', [
            'name' => 'title',
            'value' => isset($contact) ? $contact->title : '',
        ])
        @include('components/formBuilder', [
            'name' => 'content',
            'value' => isset($contact) ? $contact->content : '',
        ])
        @include('components/formBuilder', [
            'name' => 'link',
            'value' => isset($contact) ? $contact->link : '',
        ])
        @include('components/formBuilder', [
            'name' => 'seq',
            'value' => isset($contact) ? $contact->seq : '',
        ])
        @include('components/formBuilder', [
            'name' => 'contact_status',
            'label' => __('validation.attributes.status'),
            'type' => 'select',
            'value' => isset($contact) ? $contact->contact_status : '',
            'selectOptions' => App\Enums\ContactStatus::getItems(),
        ])
        @include('components/formBuilder', [
            'type' => 'submit',
            'name' => 'submit',
            'value' => isset($contact) ? __('Edit') : __('Create'),
            'size' => 2,
            'label' => '',
            'class' => 'btn btn-primary w-100',
        ])
    </form>
@endsection
