<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}"{{ (!empty($htmlAttribute)) ? $htmlAttribute : '' }}>
<head>
	@include('layout.partial.head')
</head>
<body id="top" class="{{ (!empty($bodyClass)) ? $bodyClass : '' }}">
    {{-- Loader --}}
    <div class="loading"></div>
	<!-- BEGIN #app -->
	<div id="app" class="app app-content-full-height app-footer-fixed {{ (!empty($appClass)) ? $appClass : '' }}">
		@include('layout.partial.header')
            @includeWhen(empty($sidebarHide), 'layout.partial.sidebar')
        <div id="content" class="app-content">
            <div class="container-fluid">
                @include('layout.partial.title')
	            @yield('content')
                @include('layout.partial._toast')

            </div>
        </div>

        {{--
            @includeWhen(!empty($footer), 'layout.partial.footer')
		    @include('layout.partial.footer')
		--}}
	</div>
	<!-- END #app -->
	@include('layout.partial.scroll-top-btn')
	@include('layout.partial.scripts')

    @include('layout.partial._notification')

</body>
</html>







