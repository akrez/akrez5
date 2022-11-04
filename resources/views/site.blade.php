<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon" />
    <link rel="shortcut icon" href="{{asset('favicon.ico')}}">
    <title>@yield('title', config('app.name'))</title>
    <!-- Bootstrap 5 -->
    <link rel="stylesheet" href="{{asset('dist/bootstrap-5.0.2-dist/css/bootstrap.rtl.min.css')}}">
    <!-- Font Sahel -->
    <link rel="stylesheet" href="{{asset('dist/vazirmatn-v32.102/Vazirmatn-font-face.css')}}">
    <!-- All -->
    <link rel="stylesheet" href="{{asset('dist/fontawesome-free-5.15.4-web/css/all.min.css')}}">
    <!-- style -->
    <link rel="stylesheet" href="{{asset('css/style.css')}}">
    @yield('POS_HEAD')
</head>

<body dir="rtl">

    @yield('POS_BEGIN')

    <nav class="navbar navbar-expand-lg navbar-light bg-light mb-3 rounded rounded-3">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">وبـلاگ فروشـگاهـی اکــرز</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">

                    @guest
                    @if (Route::has('login'))
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                    </li>
                    @endif

                    @if (Route::has('register'))
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                    </li>
                    @endif
                    @else
                    <li class="nav-item dropdown">

                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ Auth::user()->name }}
                        </a>

                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                {{ __('Logout') }}
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        @hasSection('header')
        <h1>@yield('header')</h1>
        @endif
        @yield('content')
    </div>

    <!-- jQuery -->
    <script src="{{asset('dist/jquery-3.6.0/jquery.min.js')}}"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="{{asset('dist/jquery-ui-1.13.1/jquery-ui.min.js')}}"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
        $.widget.bridge('uibutton', $.ui.button)
    </script>
    <!-- Bootstrap 5 -->
    <script src="{{asset('dist/bootstrap-5.0.2-dist/js/bootstrap.bundle.js')}}"></script>

    @yield('POS_END')
</body>

</html>