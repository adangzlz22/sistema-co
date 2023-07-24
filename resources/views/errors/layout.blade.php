<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('title')</title>
        @include('layout.partial.head')
    </head>
    <body>
        <div class="d-flex align-items-center h-100">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-sm-4">
                        <div class="text-center">

                            <img src="{{ asset('images/logo.png') }}" style="height: 10rem;" class="mb-2">


                            <h1 style="font-size: 8rem; line-height: 1">
                                <span style="font-size: 1rem;" class="d-block">Error</span>
                                @yield('code')
                            </h1>
                            <p  style="font-size: 1.1rem;" class="mb-5">
                                @yield('message')
                            </p>

                            <p class="mb-0">
                                <a href="{{ route('home') }}" class="btn btn-primary">
                                    Regresar al inicio
                                </a>
                            </p>

                            <img src="{{ asset('images/logo-sonora.svg') }}" style="height: 5rem;" class="mt-5">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
