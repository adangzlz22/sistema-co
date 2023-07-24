@extends('layout.auth')

@section('title','Autentificación de Usuarios')

@section('description')
    <h3 class="text-white mb-4">{{ env('APP_NAME_LARGE') }}</h3>
    <p class="text-4 text-white lh-base mb-5">
    </p>
@endsection

@section('content')


    <div class="row g-0">
        <div class="col-10 col-lg-9 col-xl-8 mx-auto">

            <h3 class="fw-600 mb-4">Iniciar sesión</h3>
            <form method="POST" action="{{ route('login_post') }}">
                @csrf




                @if (count($errors))
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>
                                <span class="text-danger fw-normal" role="alert">
                                  <strong>{{ $error }}</strong>
                                </span>
                            </li>

                        @endforeach
                    </ul>
                @endif




                <div class="mb-3">
                    <label for="email" class="form-label">Correo electrónico</label>
                    <input type="email" class="form-control form-control-lg" id="email" name="email" value="{{ old('email') }}" required="" placeholder="Ingresa con tu correo electrónico">
                    @error('email')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Contraseña</label>
                    <input type="password" class="form-control form-control-lg" id="password" name="password" value="{{ old('password') }}" required="" placeholder="Ingresa tu contraseña">

                    @error('password')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                    @enderror

                </div>

                <div class="row mt-4">
                    <div class="col">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                            <label class="form-check-label" for="remember">
                                Recordarme
                            </label>
                        </div>


                    </div>
                    <div class="col text-end"><a href="{{ route('password.request') }}">¿Olvidaste tu contraseña?</a></div>
                </div>
                
                <div class="d-grid my-4" align="center">
                    <div class="g-recaptcha" data-sitekey="{{ env('GRK') }}"></div>
                </div>

                <div class="d-grid my-4">
                    <button type="submit" class="btn btn-primary btn-lg shadow-sm">
                    Ingresar
                    </button>
                </div>



            </form>





        </div>
    </div>


<script src='https://www.google.com/recaptcha/api.js'></script>

@endsection
