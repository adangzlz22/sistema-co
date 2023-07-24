<!-- BEGIN #footer -->
<div id="footer" class="app-footer">

    <div class="container-fluid">
        <div class="row gx-3">
            <div class="col-sm-1 text-end">
                <img src="{{ asset('images/logo-sonora-escudo-white.svg') }}" class="logo d-block ms-auto">
            </div>
            <div class="col-sm-8 border-start border-light">
                <p class="lh-sm small text-justify mb-0">
                    {!! env('APP_FOOTER')  !!}
                    @include('layout.partial._terms',['btnType'=>'btn-outline-light btn-sm'])
                </p>
            </div>

            <div class="col-sm-2 text-end">
                @include('layout.partial._phone',['btnType'=>'btn-outline-light btn-sm'])
            </div>
        </div>
    </div>

</div>
<!-- END #footer -->
