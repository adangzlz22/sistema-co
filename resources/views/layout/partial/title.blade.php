@yield('breadcrumbs')


<div class="row g-3">
    <div class="col-sm-9">
        <h1 class="page-header">
            @yield('title')
            <small>@yield('description')</small>
        </h1>
    </div>
    <div class="col-sm-3 text-end">
        @yield('title-action')
    </div>
</div>


@include('layout.partial._error_messages')
