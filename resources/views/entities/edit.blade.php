@extends('layout.default')
@section('title', 'Editar institución')
@section('description', 'Actualiza los datos de una institución en específico')

@section('breadcrumbs')
    {{ Breadcrumbs::render('entities.edit', $model) }}
@endsection

@push('css')

@endpush

@push('scripts')
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    {!! JsValidator::formRequest('App\Http\Requests\EntitiesEditRequest', '#frmEntities'); !!}
@endpush

@section('content')

    {!! Form::open(['route'=>['entities.update', $model], 'method' => 'PUT', 'autocomplete'=>'off', 'id' => 'frmEntities']) !!}

    <div class="card">
        <div class="card-body">

            <div class="row g-3">
                <div class="col-sm-2">
                    <div class="form-group">
                        {!! Form::label('acronym','Siglas:',['class'=>'form-label']) !!}
                        {!! Form::text('acronym', $model->acronym, ['class'=>'form-control', 'placeholder'=>'Escribe las siglas de la institución','required']) !!}
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="form-group">
                        {!! Form::label('name','Nombre:',['class'=>'form-label']) !!}
                        {!! Form::text('name', $model->name, ['class'=>'form-control', 'placeholder'=>'Escribe el nombre de la institución','required']) !!}
                    </div>
                </div>
                
                <div class="col-sm-4">
                    <div class="form-group">
                        {!! Form::label('name','Tipo de institución:',['class'=>'form-label']) !!}
                        {!! Form::select('entities_type', $arrEntitiesTypes, $model->entities_types_id, ['class'=>'form-control picker select2', 'id'=>'entities_type', 'placeholder'=> 'Seleccione tipo institución']) !!}
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="form-group">
                        {!! Form::label('job','Puesto:',['class'=>'form-label']) !!}
                        {!! Form::text('job', $model->job, ['class'=>'form-control', 'placeholder'=>'Escribe el telefono de la institución','required']) !!}
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="form-group">
                        {!! Form::label('holder','Titular:',['class'=>'form-label']) !!}
                        {!! Form::text('holder', $model->holder, ['class'=>'form-control', 'placeholder'=>'Escribe el nombre del titular de la institución','required']) !!}
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="form-group">
                        {!! Form::label('email','Correo electrónico:',['class'=>'form-label']) !!}
                        {!! Form::text('email', $model->email, ['class'=>'form-control', 'placeholder'=>'Escribe el correo electrónico de la institución','required']) !!}
                    </div>
                </div>

                <div class="col-sm-12">
                    <div class="form-group">
                        {!! Form::label('name','Tipo de reunión:',['class'=>'form-label']) !!}<br />
                        @foreach ($arrMeetingTypes as $i => $meetingType)
                            <div class="form-check form-check-inline">
                                <input 
                                    class="form-check-input" 
                                    type="checkbox" 
                                    id="meetingType_{{$i}}" 
                                    name="meeting_type[]" 
                                    value="{{$i}}" 
                                    @foreach ($model->meeting_types as $model_meeting_type)
                                        @if($model_meeting_type->id == $i)
                                            checked 
                                        @endif
                                    @endforeach
                                />
                                <label class="form-check-label" for="meetingType_{{$i}}">{{ $meetingType }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="card-footer">
            <div class="text-right">
                <a href="{{ route('entities.index') }}" type="button" class="btn btn-default">Regresar</a>
                {!! Form::submit('Editar', ['class'=>'btn btn-info']) !!}
            </div>
        </div>
    </div>

    {!! Form::close() !!}

@endsection


