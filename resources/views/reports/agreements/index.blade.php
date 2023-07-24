@extends('layout.default')

@section("title", 'Reporte de acuerdos')
@section('description', 'Generar el reporte de acuerdos')

@section('breadcrumbs')
    {{--{{ Breadcrumbs::render('reports.agreements') }}--}}
@endsection


@section('content')

    {{ Form::open([ 'route' => ['reports.test',['download'=>'pdf'] ], 'method' => 'POST', 'id' => 'frmIndex' ]) }}
    <div class="card card-body mb-3">

        <div class="row g-3">

            <div class="col-9">
                <div id="summernote"></div>
            </div>

            <div class="col-3">
                <h3>Variables:</h3>
                <p class="font-monospace fw-bolder">
                    @NombreCompleto
                    <span class="mdi mdi-content-copy float-end mdi-18px" style="cursor: pointer;" onclick="CopyToClipboard('@NombreCompleto')"></span>
                </p>
                <p class="font-monospace fw-bolder">
                    @Trabajo
                    <span class="mdi mdi-content-copy float-end mdi-18px" style="cursor: pointer;" onclick="CopyToClipboard('@Trabajo')"></span>
                </p>
            </div>

            <div class="col-12 col-sm-12 text-end">

                {!! Form::submit('Generar PDF', ['class'=>'btn btn-info']) !!}
            </div>

        </div>

    </div>

    {!! Form::close() !!}
@endsection

@push("css")
    <link href="{{ asset('js/plugins/summer-note/summernote-lite.css') }}" rel="stylesheet">
@endpush

@push("scripts")

    <script src="{{ asset('js/plugins/summer-note/summernote-lite.min.js') }}"></script>
    <script src="{{ asset('js/plugins/summer-note/lang/summernote-es-ES.js') }}"></script>
    <script>
        $(function(){
            $('#summernote').summernote({
                lang: 'es-ES', // default: 'en-US',
                height: 600
            });
        })

    </script>


@endpush
