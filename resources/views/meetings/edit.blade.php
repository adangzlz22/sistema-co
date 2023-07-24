@extends('layout.default')
@section('title', 'Editar reunión')
@section('description', 'Actualiza los datos de la reunión')

@section('breadcrumbs')
    {{ Breadcrumbs::render('meetings.edit', $model) }}
@endsection

@push('css')

@endpush

@push('scripts')
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    {!! JsValidator::formRequest('App\Http\Requests\MeetingCreateRequest', '#frmMeeting'); !!}
@endpush

@section('content')

    {!! Form::open(['route'=>['meetings.update', $model], 'method' => 'PUT', 'autocomplete'=>'off', 'id' => 'frmMeeting']) !!}
        {{ Form::hidden('meeting_id_', $model->id) }}
        <div class="card">
            <div class="card-body">
                <div class="row g-3">
                    <div class="row g-3">
                        <div class="col-sm-2">
                            <div class="form-group">
                                {!! Form::label('folio', 'Folio:',['class'=>'form-label']) !!}
                                {!! Form::text('folio', $model->folio, ['class'=>'form-control', 'disabled']) !!}
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group">
                            {!! Form::label('meeting_type', 'Tipo de reunión:',['class'=>'form-label']) !!}
                            {!! Form::select('meeting_type', $arrMeetingTypes, $model->meeting_type_id, ['class'=>'form-control picker select2', 'id'=>'meeting_type', 'placeholder'=> 'Seleccione tipo de reunión']) !!}
                        </div>
                    </div>
    
                    <div class="col-sm-3">
                        <div class="form-group">
                            {!! Form::label('date','Fecha de la reunión:',['class'=>'form-label']) !!}
                            {!! Form::date('date', $model->meeting_date, ['class'=>'form-control', 'placeholder'=>'Seleciona la fecha de la reunión','required']) !!}
                        </div>
                    </div>
    
                    <div class="col-sm-3">
                        <div class="form-group">
                            {!! Form::label('time_start','Hora inicio de la reunión:',['class'=>'form-label']) !!}
                            {!! Form::time('time_start', $model->meeting_time, ['class'=>'form-control', 'placeholder'=>'Selecciona la hora de inico de la reunión','required']) !!}
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group">
                            {!! Form::label('time_end','Hora término de la reunión:',['class'=>'form-label']) !!}
                            {!! Form::time('time_end', $model->meeting_time_end, ['class'=>'form-control', 'placeholder'=>'Selecciona la hora de término de la reunión','required']) !!}
                        </div>
                    </div>
    
                    <div class="col-sm-4">
                        <div class="form-group">
                            {!! Form::label('modality', 'Modalidad:', ['class'=>'form-label']) !!}
                            {!! Form::select('modality', $arrModalities, $model->modality_id, ['class'=>'form-control picker select2', 'id'=>'modality_id', 'placeholder'=> 'Seleccione la modalidad']) !!}
                        </div>
                    </div>
    
                    <div class="col-sm-4" id="div-place">
                        <div class="form-group">
                            {!! Form::label('place', 'Lugar de la reunión:', ['class'=>'form-label']) !!}
                            <a href="{{ route('places.create') }}" title="Agregar lugar"><i class="mdi mdi-map-marker-plus"></i></a>
                            {!! Form::select('place', $arrPlaces, $model->place_id, ['class'=>'form-control picker select2', 'placeholder'=>'Escribe el lugar de la reunión','required']) !!}
                        </div>
                    </div>
    
                    <div class="col-sm-4" id="div-link">
                        <div class="form-group">
                            {!! Form::label('link', 'Link de la Reunión:', ['class'=>'form-label']) !!}
                            {!! Form::text('link', $model->link, ['class'=>'form-control', 'placeholder'=>'Escribe el link de la reunión','required']) !!}
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <div class="text-right">
                    <a href="{{ route('meetings.index') }}" type="button" class="btn btn-default">Regresar</a>
                    {!! Form::submit('Editar', ['class'=>'btn btn-info']) !!}
                </div>
            </div>
        </div>
    {!! Form::close() !!}
@endsection

@push("scripts")
<script>
    function setPlacesForModalities(){
        
        let modality_id = $("#modality_id").val();
        
        switch (parseInt(modality_id)) {
            case 1:
                $("#div-place").show();
                $("#div-link").hide();
            break;
                
            case 2:
                $("#div-link").show();
                $("#div-place").hide();
            break;
            
            case 3:
                $("#div-link").show();
                $("#div-place").show();
            break;
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        $('#modality_id').on('change', function () {
            setPlacesForModalities();
        });

        setPlacesForModalities();
    });
</script>
@endpush