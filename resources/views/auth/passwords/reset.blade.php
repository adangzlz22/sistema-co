@extends('layout.auth')

@section('description')


    <h1 class="text-11 text-white mb-4">Nueva contraseña</h1>
    <p class="text-4 text-white lh-base mb-5">
        Haz solicitado el restablecimiento de tu contraseña. Ingresa tu nueva contraseña y da clic en Guardar.
    </p>

@endsection


@section('content')



    <div class="row g-0">
        <div class="col-10 col-lg-9 col-xl-8 mx-auto">
            <h3 class="fw-600 mb-4">Nueva Contraseña</h3>

            <form method="POST" action="{{ route('password.update') }}">
                @csrf


                <input type="hidden" name="token" value="{{ $token }}">

                <div class="mb-3">
                    <label for="email" class="form-label">Correo Electrónico</label>

                    <input id="email" type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>

                    @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Contraseña</label>

                    <input id="password" type="password" class="form-control form-control-lg @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                    @error('password')
                    <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password-confirm" class="form-label">Confirmar Contraseña</label>
                    <input id="password-confirm" type="password" class="form-control form-control-lg" name="password_confirmation" required autocomplete="new-password">

                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-lg">
                        Restablecer Contraseña
                    </button>
                </div>



            </form>
        </div>
    </div>




@endsection
