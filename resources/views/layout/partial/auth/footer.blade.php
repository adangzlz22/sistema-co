<div class="row justify-content-center">
    <div class="col-10 col-lg-10">
        <div class="auth-footer text-light-400">
            <div class="row">

                <div class="col-sm-2">
                    <img src="{{asset('images/logo-sonora-white.svg')}}" />
                </div>

                <div class="col-sm-10 border-start border-light">
                    <p class="lh-sm small text-justify mb-0">
                        {!! env('APP_FOOTER')  !!} |
                        @include('layout.partial._terms',['btnType'=>'btn-link py-0 px-0 text-gray-100 btn-sm'])
                    </p>
                </div>


            </div>
        </div>

    </div>

</div>






