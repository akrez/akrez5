@extends('site')

@section('content')
<div class="align-items-center rounded-3 border overflow-hidden py-5 bg-body">
    <div class="row">
        <div class="col-lg-7 text-center">
            <h1 class="display-4 fw-bold lh-1">تجارت بدون مرز!</h1>
            <figure class="lead p-3 mb-0 rounded">
                <blockquote class="blockquote">
                    <p>
                        اگر کسب و کار شما اینترنتی نیست متاسفانه شما صاحب یک کسب و کار از رده خارج و رو به زوال هستید اگر کسب و کارتان در اینترنت نباشد به زودی از بازار هم حذف خواهید شد
                    </p>
                </blockquote>
                <figcaption class="blockquote-footer mb-0">
                    <cite title="Source Title">بیل گیتس</cite>
                    بنیان گذار مایکروسافت
                </figcaption>
            </figure>
        </div>
        <div class="col-lg-4 m-auto p-0">
            <img class="rounded-3 img-fluid shadow-lg" src="{{asset('images/dashboard.jpg')}}">
        </div>
    </div>
</div>
@endsection