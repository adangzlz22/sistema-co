@extends('layout.auth')



@section('description')

    <h1 class="text-11 text-white mb-4">Looks like you're new here!</h1>
    <p class="text-4 text-white lh-base mb-5">Join our group in few minutes! Sign up with your details to get started</p>

@endsection

@section('content')



    <div class="row g-0">
        <div class="col-10 col-lg-9 col-xl-8 mx-auto">
            <h3 class="fw-600 mb-4">Registrarse</h3>

            <form method="POST" action="{{ route('register') }}">
            @csrf

                <div class="mb-3">
                    <label for="name" class="form-label">Nombre</label>
                    <input id="name" type="text" class="form-control form-control-lg @error('name') is-invalid @enderror" name="name" placeholder="Nombre Completo" value="{{ old('name') }}" required autocomplete="name" autofocus>
                    @error('name')
                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                    @enderror
                </div>


                <div class="mb-3">
                    <label for="email" class="form-label">Correo Electronico</label>
                    <input id="email" type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" placeholder="Correo Electrónico" name="email" value="{{ old('email') }}" required autocomplete="email">

                    @error('email')
                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Contraseña</label>
                    <input id="password" type="password" placeholder="Contraseña" class="form-control form-control-lg @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                    @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Confirmar Contraseña</label>
                    <input id="password_confirmation" type="password" placeholder="Confirmar Contraseña" class="form-control form-control-lg @error('password_confirmation') is-invalid @enderror" name="password_confirmation" required autocomplete="password_confirmation">
                    @error('password_confirmation')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="d-grid my-4">

                    <button type="submit" class="btn btn-primary btn-lg btn-block shadow">
                        Registrar
                    </button>

                </div>


            </form>



        </div>
    </div>


@endsection
