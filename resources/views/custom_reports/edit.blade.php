@extends('layout.default')
@section('title', 'Editar reporte')
@section('description', 'Actualiza los datos del reporte en espécifico')

@section('breadcrumbs')
    {{ Breadcrumbs::render('custom_reports.edit', $model) }}
@endsection

@push('css')

@endpush

@push('scripts')
@endpush

@section('content')

    {!! Form::open(['route'=>['custom_reports.edit_post',$model], 'method' => 'PUT', 'autocomplete'=>'off']) !!}

    <div class="card">
        <div class="card-body">

            <div class="row g-3">

                <div class="col-9">
                    <h3>Cuerpo del reporte:</h3>
                    {!! Form::textarea('html_report', $model->html_report, ['class'=>'summernote', 'placeholder'=>'Descripción','required']) !!}
                </div>

                <div class="col-3">
                    <h3>Parametros:</h3>

                    @foreach(explode('|', $model->parameters) as $item)
                        <p class="font-monospace fw-bolder">
                            {{ $item }}
                            <span class="mdi mdi-content-copy float-end mdi-18px" style="cursor: pointer;" onclick="CopyToClipboard('{{ $item }}')"></span>
                        </p>
                    @endforeach
                </div>

            </div>
        </div>

        <div class="card-footer">
            <div class="text-right">
                <a href="{{ route('custom_reports.index') }}" type="button" class="btn btn-default">Regresar</a>
                {!! Form::submit('Editar', ['class'=>'btn btn-warning']) !!}
            </div>
        </div>
    </div>


    {!! Form::close() !!}

@endsection

@push("css")
    {{--<link href="{{ asset('js/plugins/summer-note/summernote-lite.css') }}" rel="stylesheet">--}}
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
@endpush

@push("scripts")

    {{--<script src="{{ asset('js/plugins/summer-note/summernote-lite.min.js') }}"></script>--}}
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
    <script src="{{ asset('js/plugins/summer-note/lang/summernote-es-ES.js') }}"></script>
    <script>
        $(function(){

            $(".summernote").summernote({
                lang: 'es-ES',
                height: 600,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'italic', 'underline', 'clear']],
                    ['fontname', ['fontname']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['height', ['height']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture', 'hr']],
                    ['view', ['fullscreen', 'codeview']],
                    ['help', ['help']]
                ]
            });



        })

    </script>


@endpush


