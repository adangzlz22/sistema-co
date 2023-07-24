<!-- BEGIN #header -->
<div id="header" class="app-header">
    <!-- BEGIN mobile-toggler -->
    <div class="mobile-toggler">
        <button type="button" class="menu-toggler" data-toggle="sidebar-mobile">
            <span class="bar"></span>
            <span class="bar"></span>
        </button>
    </div>
    <!-- END mobile-toggler -->

    <!-- BEGIN brand -->
    <div class="brand">
        <div class="desktop-toggler">
            <button type="button" class="menu-toggler" data-toggle="sidebar-minify" title="Menú">
                <span class="bar"></span>
                <span class="bar"></span>
                <span class="bar"></span>
            </button>
        </div>


        <a class="brand-logo" href="{{ url('/') }}" >
            <img src="{{ asset('images/logo_oe.png') }}" style="min-height: 110px;" alt="{{ config('app.name') }}"> 
        </a>


    </div>
    <!-- END brand -->

    <!-- BEGIN menu -->
    <div class="menu">
        <form class="menu-search" method="POST" name="header_search_form">
            {{-- <div class="menu-search-icon"><i class="fa fa-search"></i></div> --}}
            {{-- <div class="menu-search-input">
                @include('layout.partial._search')
            </div> --}}
        </form>


        @php
            //$notifications =  auth()->user()->unreadNotifications->count();
            $total = Auth::user()->unreadNotifications->count() ?? 0;
        @endphp
            <div class="menu-item dropdown">
            <a id="show_notifications" href="#" data-bs-toggle="dropdown" data-bs-display="static" class="menu-link" title="Ver notificaciones">
                <div class="menu-icon"><i class="fa fa-bell nav-icon"></i></div>
                <div class="menu-label" id="unread_notifications">{{ $total }}</div>
            </a>
            <div class="dropdown-menu dropdown-menu-end dropdown-notification">
                <h6 class="dropdown-header text-gray-900 mb-1">Notificaciones</h6>
                <div id="notification_content" class="notification_content">

                </div>
            </div>
        </div>



        <div class="menu-item dropdown">
            <a href="#" data-bs-toggle="dropdown" title="@if(Auth::user()->email_verified_at != null) Usuario verificado @else Usuario No Verificado @endif" data-bs-display="static" class="menu-link">
                <div class="menu-img @if(Auth::user()->email_verified_at != null) online @endif ">
                    @if(Auth::user()->avatar == null)
                        <div class="d-flex align-items-center justify-content-center w-100 h-100 bg-gray-300 text-gray-100 rounded-circle overflow-hidden">
                            <i class="fa fa-user fa-2x mb-n3"></i>
                        </div>
                    @else
                        <img style="width: 2.5rem;" class="rounded-circle me-2" src="{{ asset('/images/avatars/'.Auth::user()->avatar) }}">

                    @endif
                </div>

                <div class="menu-text lh-1">

                   {{ Auth::user()->name }}  <span class="mdi mdi-chevron-down"></span>
                    <small class="d-block fw-light text-muted pt-1">{{ Auth::user()->organism->name ?? Auth::user()->roles->first()->name ?? '' }}</small>

                </div>
            </a>
            <div class="dropdown-menu dropdown-menu-end me-lg-3">
                <a class="dropdown-item d-flex align-items-center" href="{{ route('profile') }}">Mi perfil <i class="mdi mdi-account-circle ms-auto text-gray-400 fs-16px"></i></a>

                {{--
                <a class="dropdown-item d-flex align-items-center" href="#">Buzón de Correo <i class="mdi mdi-email-outline ms-auto text-gray-400 fs-16px"></i></a>
                <a class="dropdown-item d-flex align-items-center" href="#">Calendario <i class="mdi mdi-calendar ms-auto text-gray-400 fs-16px"></i></a>
                <a class="dropdown-item d-flex align-items-center" href="#">Ajustes <i class="mdi mdi-cog ms-auto text-gray-400 fs-16px"></i></a>
                --}}

                <div class="dropdown-divider"></div>
                <a class="dropdown-item d-flex align-items-center" href="{{ route('system.logoff') }}"
                   onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                    Salir <i class="mdi mdi-logout-variant fa-fw ms-auto text-gray-400 fs-16px"></i>
                </a>

                <form id="logout-form" action="{{ route('system.logoff') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
        </div>
    </div>
    <!-- END menu -->
</div>
<!-- END #header -->
