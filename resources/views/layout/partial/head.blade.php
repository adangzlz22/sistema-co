<meta charset="utf-8" />
<title>{{ env('APP_NAME')  }} | @yield('title')</title>
<meta name="viewport" content="width=device-width, initial-scale=1" />
<meta name="description" content="@yield('metaDescription')" />
<meta name="author" content="@yield('metaAuthor')" />
<meta name="keywords" content="@yield('metaKeywords')" />
<meta name="csrf-token" content="{{ csrf_token() }}">
<link href="{{ asset('images/favicon.png') }}" rel="shortcut icon" type="image/vnd.microsoft.icon" />
{{-- <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests"> --}}
@stack('metaTag')

<!-- ================== BEGIN BASE CSS STYLE ================== -->
<link href="{{ asset('css/vendor.min.css') }}" rel="stylesheet">
<link href="{{ asset('css/app.min.css') }}" rel="stylesheet">

{{-- añadidos --}}
<link href="{{ asset('js/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.css') }}" rel="stylesheet">
<!-- ================== END BASE CSS STYLE ================== -->
<style>
table.collapsed .dtr-control::before{
    border: none !important;
    border-radius: 0em !important;
    box-shadow:none !important;
}
table.collapsed td.dtr-control:before {
    content: url("{{ asset('images/icon/plus.svg') }}") !important;
    background: none !important;
}
table.collapsed tr.dt-hasChild td.dtr-control:before {
    content: url("{{ asset('images/icon/minus.svg') }}") !important;
    background: none !important;
}
</style>
@stack('css')
