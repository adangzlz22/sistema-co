<section class="system-messages">

    <div class="container-fluid">
        <div>
            <input id="notification" type="hidden" value="{{ json_encode(session('flash_notification', collect())->toArray()) }}" />
            {{-- {{ dd(Session('flash_notification')->toArray()) }} --}}
            {{-- @include('flash::message') --}}

        </div>
    </div>
</section>

<section class="system-error-messages">


    <input id="notification_error" type="hidden" value="{{ json_encode($errors->toArray()) }}" />
{{--    @if(count($errors) > 0)--}}
{{--        <div class="alert alert-danger">--}}
{{--            <p><strong><i class="mdi mdi-alert-box"></i> Se encontrarón los siguientes errores:</strong></p>--}}
{{--            <ul>--}}
{{--                @foreach($errors->all() as $error)--}}
{{--                    <li> {{$error}}</li>--}}
{{--                @endforeach--}}
{{--            </ul>--}}
{{--        </div>--}}
{{--    @endif--}}

</section>
