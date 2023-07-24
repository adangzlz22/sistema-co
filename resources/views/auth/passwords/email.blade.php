@extends('layout.auth')

@section('title','Reestablece tu contraseña')

@section('description')

    <h1 class="text-11 text-white mb-4">Restablecer contraseña</h1>
    <p class="text-4 text-white lh-base mb-5">
        Deberás ingresar el correo electrónico, con el cual te diste de alta en el sistema.
        En breve recibirás un mensaje con el link para crear tu nueva contraseña.
    </p>


@endsection

@section('content')




    <div class="row g-0">
        <div class="col-10 col-lg-9 col-xl-8 mx-auto">

            @if (session('status'))
                <div class="alert alert-success mb-3" role="alert">
                    {{ session('status') }}
                </div>
            @endif


            <h3 class="fw-600 mb-4">Restablecer contraseña</h3>
            <form method="POST" action="{{ route('password.email') }}">
            @csrf

                <div class="mb-3">
                    <label for="email" class="col-md-4 col-form-label text-md-right">Correo Electrónico</label>
                    <input id="email" type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                    @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="mb-3" align='center'>
                    <div class="g-recaptcha @error('g-recaptcha-response') is-invalid @enderror" data-sitekey="{{ env('GRK') }}"></div>
                    @error('g-recaptcha-response')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="row">
                    <a href="{{ route('login') }}" type="button" class="btn btn-default btn-lg shadow-sm col-4 m-1">Regresar</a>
                    <button type="submit" class="btn btn-primary btn-lg shadow-sm col-7 m-1">
                        Enviar
                    </button>
                </div>


            </form>


        </div>

    </div>

<script src='https://www.google.com/recaptcha/api.js'></script>
@endsection
