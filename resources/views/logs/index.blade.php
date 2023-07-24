@extends('layout.default')

@section("title", 'Bitácora')
@section('description', 'Ver listado de movimientos en el sistema')
@section('content')

    {{ Form::open([ 'route' => ['logs.index' ], 'method' => 'GET', 'id' => 'frmIndex' ]) }}
    <div class="card card-body mb-3">

        <div class="row g-3">

            <div class="col-12 col-sm-4">
                {!! Form::text('tbUser', $request->tbUser, ['class'=>'form-control', 'placeholder'=>'Buscar por nombre de usuario']) !!}
            </div>

            <div class="col-12 col-sm-4">
                <div class="input-group input-daterange" id="datepicker">
                    <input type="text" class="form-control" name="init_date" value="{{ $init_date }}" placeholder="Fecha inicio" />
                    <span class="input-group-text">hasta</span>
                    <input type="text" class="form-control" name="end_date" value="{{ $end_date }}" placeholder="Fecha final" />
                </div>
            </div>

            <div class="col-12 col-sm-4 text-end">
                <a href="{{ route('logs.index') }}" class="btn btn-default">Restablecer</a>
                {!! Form::submit('Filtrar', ['class'=>'btn btn-info']) !!}
            </div>

        </div>

    </div>

    @include("logs._index")
    {!! Form::close() !!}
@endsection

@push("css")
    <link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
@endpush


@push("scripts")
    <script src="{{ asset('assets/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
    <script src=""></script>
    <script>
        $(function(){
            $('#datepicker').datepicker({
                autoclose: true
            });
        })
    </script>
@endpush
