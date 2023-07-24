@extends('layout.default')

@section('title', 'Crear institución')
@section('description', '')

@section('breadcrumbs')
    {{Breadcrumbs::render('entities.create') }}
@endsection

@push('css')

@endpush

@push('scripts')
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    {!! JsValidator::formRequest('App\Http\Requests\EntitiesCreateRequest', '#frmEntities'); !!}
@endpush

@section('content')

    {!! Form::open(['route'=>'entities.store', 'method' => 'POST', 'id' => 'frmEntities']) !!}

    <div class="card">
        <div class="card-body">

            <div class="row g-3">

                <div class="col-sm-2">
                    <div class="form-group">
                        {!! Form::label('acronym','Siglas:',['class'=>'form-label']) !!}
                        {!! Form::text('acronym', null, ['class'=>'form-control', 'placeholder'=>'Escribe las siglas de la institución','required']) !!}
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="form-group">
                        {!! Form::label('name','Nombre:',['class'=>'form-label']) !!}
                        {!! Form::text('name', null, ['class'=>'form-control', 'placeholder'=>'Escribe el nombre de la institución','required']) !!}
                    </div>
                </div>
                
                <div class="col-sm-4">
                    <div class="form-group">
                        {!! Form::label('name','Tipo de institución:',['class'=>'form-label']) !!}
                        {!! Form::select('entities_type', $arrEntitiesTypes, old('entities_type') ?? [], ['class'=>'form-control picker select2', 'id'=>'entities_type', 'placeholder'=> 'Seleccione tipo institución']) !!}
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="form-group">
                        {!! Form::label('job','Puesto:',['class'=>'form-label']) !!}
                        {!! Form::text('job', null, ['class'=>'form-control', 'placeholder'=>'Escribe el puesto','required']) !!}
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="form-group">
                        {!! Form::label('holder','Titular:',['class'=>'form-label']) !!}
                        {!! Form::text('holder', null, ['class'=>'form-control', 'placeholder'=>'Escribe el nombre del titular de la institución','required']) !!}
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="form-group">
                        {!! Form::label('email','Correo electrónico:',['class'=>'form-label']) !!}
                        {!! Form::text('email', null, ['class'=>'form-control', 'placeholder'=>'Escribe el correo electrónico de la institución','required']) !!}
                    </div>
                </div>

                <div class="col-sm-12">
                    <div class="form-group">
                        {!! Form::label('name','Tipo de reunión:',['class'=>'form-label']) !!}<br />
                        @foreach ($arrMeetingTypes as $i => $meetingType)
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="meetingType_{{$i}}" name="meeting_type[]" value="{{$i}}">
                                <label class="form-check-label" for="meetingType_{{$i}}">{{ $meetingType }}</label>
                            </div>
                        @endforeach
                    </div>
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
                <a href="{{ route('entities.index') }}" type="button" class="btn btn-default">Regresar</a>
                {!! Form::submit('Guardar', ['class'=>'btn btn-info']) !!}
            </div>
        </div>

    </div>

    {!! Form::close() !!}

@endsection



