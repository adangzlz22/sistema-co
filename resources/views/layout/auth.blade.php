<!doctype html>
<html lang="{{ app()->getLocale() }}"{{ (!empty($htmlAttribute)) ? $htmlAttribute : '' }}>
<head>
    @include('layout.partial.head')
</head>
<body class="{{ (!empty($bodyClass)) ? $bodyClass : '' }}">
<div id="app" class="app auth">
    <div class="container-fluid px-0">
        <div class="row g-0 min-vh-100 min-vh-100">

        <div class="col-12 lg:col-6 md:col-6 sm:col-6 d-md-none d- h-50">
            <div class="auth-wrapp d-flex align-items-center h-50">
                <div class="auth-mask opacity-8 bg-primary"></div>
                <div class="auth-bg auth-bg-scroll"
                        style="background-image: url({{ asset('images/oficina_ejecutivo_1.jpeg') }});"></div>
                <div class="auth-content d-flex flex-column">
                    @include('layout.partial.auth.header')
                </div>
            </div>
        </div>
            <div class="col-sm-6 d-none d-md-block">

                <div class="auth-wrapp d-flex align-items-center h-100">
                    <div class="auth-mask opacity-8 bg-primary"></div>
                    <div class="auth-bg auth-bg-scroll"
                         style="background-image: url({{ asset('images/oficina_ejecutivo_1.jpeg') }});"></div>

                    <div class="auth-content w-100 min-vh-100 d-flex flex-column">
                        @include('layout.partial._toast')




                        @include('layout.partial.auth.header')


                        <div class="row my-auto justify-content-center">
                            <div class="col-10 col-lg-8 mx-auto text-center">
                                @yield('description')
                            </div>
                        </div>


                        @include('layout.partial.auth.footer')


                    </div>


                </div>

            </div>
            <div class="col-md-6 d-flex">
                <div class="container my-auto py-5">
                    <div class="row g-0">
                        <div class="col-10 col-lg-9 col-xl-8 mx-auto">
                            @include('layout.partial._error_messages')
                        </div>
                    </div>
                    @yield('content')
                    @include('layout.partial._phone',['btnType'=>'btn-circle btn-primary btn-phone'])
                </div>


            </div>
        </div>
    </div>

    {{--


    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                {{ config('app.name', 'Laravel') }}
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left Side Of Navbar -->
                <ul class="navbar-nav mr-auto">

                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ml-auto">
                    <!-- Authentication Links -->
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
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }}
                            </a>

                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">
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


    --}}

</div>
@include('layout.partial.scripts')

</body>
</html>
