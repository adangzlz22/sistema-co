@extends('layout.default')
@section('title', 'Editar asunto')
@section('description', 'Actualiza un asunto en específico')

@section('breadcrumbs')
    {{ Breadcrumbs::render('meetings.edit_subject', $model) }}
@endsection

@push('css')

@endpush

@push('scripts')
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    {!! JsValidator::formRequest('App\Http\Requests\SubjectCreateRequest', '#frmSubject'); !!}
@endpush

@section('content')

    {!! Form::open(['route'=>['meetings.update_subject', $model[0]->id], 'method' => 'PUT', 'autocomplete'=>'off', 'id' => 'frmSubject']) !!}
        {!! Form::hidden('meeting_id', $model[0]->meeting_id ) !!}
        <div class="card">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-sm-4">
                        <div class="form-group">
                            {!! Form::label('subject', 'Asunto (Tema):', ['class'=>'form-label']) !!}
                            {!! Form::text('subject', $model[0]->subject, ['class'=>'form-control', 'placeholder'=>'Escribe el asunto o tema a tratar','required']) !!}
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            {!! Form::label('expositor', 'Expositor:', ['class'=>'form-label']) !!}
                            {!! Form::text('expositor', $model[0]->expositor, ['class'=>'form-control', 'placeholder'=>'Escribe el nombre del expositor','required']) !!}
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            {!! Form::label('entity_id', 'Institución:', ['class'=>'form-label']) !!}
                            {!! Form::select('entity_id', $arrEntities, $model[0]->entity_id, ['class'=>'form-control picker select2', 'id'=>'modality', 'placeholder'=> 'Seleccione la Modalidad']) !!}
                        </div>
                    </div>
                    
                    <div class="col-sm-12">
                        <div class="form-group">
                            {!! Form::label('observation', 'Obsercaciones:', ['class'=>'form-label']) !!}
                            {!! Form::textarea('observation', $model[0]->observation, ['class'=>'form-control', 'rows' => 3, 'placeholder'=>'Escribe la observación de este asunto','required']) !!}
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <div class="text-right">
                    <a href="{{ route('meetings.subject', $model[0]->meeting_id) }}" type="button" class="btn btn-default">Regresar</a>
                    {!! Form::submit('Editar', ['class'=>'btn btn-info']) !!}
                </div>
            </div>
        </div>
    {!! Form::close() !!}

@endsection


