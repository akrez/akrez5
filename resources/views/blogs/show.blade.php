@extends('site')

@section('header', $blog->title)
@section('subheader', $blog->slug)

@section('content')
    <div class="row">
        <div class="col-md-12 mb-4">
            {{ $blog->description }}
        </div>

        <div class="col-md-12">
            {!!
             App\View\Components\AkrezGridTable::build($visits)
            ->newFieldColumn('created_at')
            ->newFieldColumn('http_code')
            ->newFieldColumn('ip')
            ->newFieldColumn('method')
            ->newFieldColumn('url')
            ->newFieldColumn('user_agent')
            ->render()
             !!}
            {{ $visits->links() }}
        </div>
    </div>
@endsection
