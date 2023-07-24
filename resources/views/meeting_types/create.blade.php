@extends('layout.default')

@section('title', 'Crear tipo de reunión')
@section('description', '')

@section('breadcrumbs')
    {{Breadcrumbs::render('meeting_types.create') }}
@endsection

@push('css')

@endpush

@push('scripts')
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    {!! JsValidator::formRequest('App\Http\Requests\MeetingTypeCreateRequest', '#frmMeetingType'); !!}
@endpush

@section('content')

    {!! Form::open(['route'=>'meeting_types.store', 'method' => 'POST', 'id' => 'frmMeetingType']) !!}

    <div class="card">
        <div class="card-body">

            <div class="row g-3">

                <div class="col-sm-2">
                    <div class="form-group">
                        {!! Form::label('acronym','Siglas:',['class'=>'form-label']) !!}
                        {!! Form::text('acronym', null, ['class'=>'form-control', 'placeholder'=>'Escribe siglas del tipo de reunión','required']) !!}
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="form-group">
                        {!! Form::label('name','Nombre:',['class'=>'form-label']) !!}
                        {!! Form::text('name', null, ['class'=>'form-control', 'placeholder'=>'Escribe el nombre del tipo de reunión','required']) !!}
                    </div>
                </div>
                
                <div class="col-sm-4">
                    {!! Form::label('color','Color:',['class'=>'form-label']) !!}
                    {!! Form::color('color', null, ['class'=>'form-control', 'placeholder'=>'Selecciona el color para identificar este tipo de reunión','required']) !!}
                    <div id="emailHelp" class="form-text">Selecciona el color para identificar este tipo de reunión.</div>
                </div>

                <div class="col-12">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="active" name="active" checked="checked">
                        <label class="form-check-label" for="gridCheck">
                            Activo
                        </label>
                    </div>
                </div>

            </div>
        </div>

        <div class="card-footer">
            <div class="text-right">
                <a href="{{ route('meeting_types.index') }}" type="button" class="btn btn-default">Regresar</a>
                {!! Form::submit('Guardar', ['class'=>'btn btn-info']) !!}
            </div>
        </div>

    </div>

    {!! Form::close() !!}

@endsection



