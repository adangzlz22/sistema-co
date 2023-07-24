@extends('layout.default')




@section('content')

    <div class="row justify-content-center">

        <div class="col-12 col-sm-6">
            <h3>Mi Perfil</h3>


            @auth

                @if($user->hasRole('ciudadano') && ($user->city_id === NULL))

                    <div class="m-auto">
                        <p class="bg-pink mb-3 mb-0 text-justify text-white p-3 border-radius shadow alert alert-warning alert-dismissible fade show border-0" role="alert">
                        <span class="d-block mr-5">
                        <span class="mdi mdi-information-outline"></span>
                        Atención, antes de poder usar este sitio web, debes proporcionar tus datos de ubicación (Municipio).
                            </span>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </p>
                    </div>
                @endif

            @endauth



            <div class="card ">
                <div class="card-header">
                    @include('profile.avatar')
                </div>
                {{--
                    <div class="card-body border-0 border-radius">
                        @include('profile.information')
                    </div>
                --}}
                <div class="card-body">
                    @include('profile.password')
                </div>
            </div>
        </div>

    </div>

@endsection


@section('scripts')


    <script defer src="https://unpkg.com/jquery-input-mask-phone-number@1.0.14/dist/jquery-input-mask-phone-number.js"></script>

    <script>
        window.addEventListener('DOMContentLoaded', function() {

            (function($) {
                $(document).ready(function () {
                    $('input#phone').usPhoneFormat({
                        format: 'xxx-xxx-xxxx',
                    });
                });
            })(jQuery);
        });
    </script>



@endsection
