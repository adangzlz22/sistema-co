@extends('layout.empty')

@section('title', '404 Error')

@section('content')
    <!-- BEGIN error -->
    <div class="error-page">
        <!-- BEGIN error-page-content -->
        <div class="error-page-content">
            <div class="error-img">
                <img src="/assets/img/page/404.svg" alt="" />
            </div>

            <h1>¡Error 404!</h1>
            <h3>La página que solicitaste no existe</h3>
            <p class="text-muted mb-2">
                Intenta con uno de los siguientes enlaces:
            </p>
            <p class="mb-4">
                <a href="/" class="text-decoration-none">Inicio</a>
                <span class="link-divider"></span>
                <a href="/" class="text-decoration-none">Registro</a>
                <span class="link-divider"></span>
                <a href="/" class="text-decoration-none">Iniciar Sesión</a>
                <span class="link-divider"></span>
                <a href="/" class="text-decoration-none">Recuperar Contraseña</a>
                <span class="link-divider"></span>
                <a href="/" class="text-decoration-none">Contacto</a>

            </p>
            <a href="/" class="btn btn-primary">Ir al inicio</a>
        </div>
        <!-- END error-page-content -->
    </div>
    <!-- END error -->
@endsection
