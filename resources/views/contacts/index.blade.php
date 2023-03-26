@extends('site')

@section('header', __('Contacts'))
@section('subheader', UserActiveBlog::attr('title'))

@section('content')
    <div class="row mb-2">
        <div class="col-md-2 pull-right">
            <a class="btn btn-success w-100" href="{{ route('contacts.create') }}">
                @lang('Create :resource', ['resource' => __('Contact')])
            </a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            {!! $contactsGridTable !!}
        </div>
    </div>
    {{ $contacts->links() }}
@endsection
