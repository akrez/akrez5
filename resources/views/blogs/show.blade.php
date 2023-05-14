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
            ->newJdfColumn('created_at')
            ->newFieldColumn('http_code')
            ->newFieldColumn('ip')
            ->newFieldColumn('user_agent')->contentAttributes(['class'=>'text-ltr'])
            ->render()
             !!}
            {{ $visits->links() }}
        </div>
    </div>
@endsection
