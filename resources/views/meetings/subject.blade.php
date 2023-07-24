@extends('layout.default')
@section('title', 'Agregar asuntos')
@section('description', 'Se agregan asuntos a una reunión en específico')

@section('breadcrumbs')
    {{ Breadcrumbs::render('meetings.subject', $model) }}
@endsection

@push('css')

@endpush

@push('scripts')
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    {!! JsValidator::formRequest('App\Http\Requests\SubjectCreateRequest', '#frmSubject'); !!}
    {!! JsValidator::formRequest('App\Http\Requests\SubjectFilesRequest', '#frmSubjectFiles'); !!}
@endpush

@section('content')

    <div class="row p-2">
        <div class="col-sm-12 p-2">
            <div class="card border-warning mb-3">
                <div class="card-header">
                    FOLIO: {{$model[0]->folio}}
                </div>
                <figure class="text-center">
                    <blockquote class="blockquote">
                        <p>{!! $model[0]->meeting_name !!}</p>
                    </blockquote>

                    <figcaption class="blockquote-footer">
                        Fecha: <cite title="Source Title">{!! date("d/m/Y", strtotime($model[0]->meeting_date)) !!} Hora: {!! $model[0]->meeting_time !!} Hrs</cite>
                    </figcaption>

                    @if(in_array($model[0]->modality_id, [1, 3]))
                        <figcaption class="blockquote-footer">
                            {!! $model[0]->place->name !!}, {!! $model[0]->place->address !!}
                        </figcaption>
                    @endif

                    @if(in_array($model[0]->modality_id, [2, 3]))
                        <figcaption class="blockquote-footer">
                            Link: <cite title="Source Title"><a href="{!! $model[0]->link !!}" target="_blank">{!! $model[0]->link !!}</a></cite>
                        </figcaption>
                    @endif
                </figure>
            </div>
        </div>
    </div>

    @can('Crear Asuntos')
        {!! Form::open(['route'=>'meetings.subject_store', 'method' => 'POST', 'id' => 'frmSubject', 'files' => true]) !!}
        <div class="card">
            <div class="card-body">
                <div class="row g-3">
                    {!! Form::hidden('meeting_id', $meeting_id) !!}
                    
                    <div class="col-sm-4">
                        <div class="form-group">
                            {!! Form::label('subject', 'Asunto (Tema):', ['class'=>'form-label']) !!}
                            {!! Form::text('subject', null, ['class'=>'form-control', 'placeholder'=>'Escribe el asunto o tema a tratar','required']) !!}
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            {!! Form::label('expositor', 'Expositor:', ['class'=>'form-label']) !!}
                            {!! Form::text('expositor', null, ['class'=>'form-control', 'placeholder'=>'Escribe el nombre del expositor','required']) !!}
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            {!! Form::label('entity_id', 'Institución:', ['class'=>'form-label']) !!}
                            {!! Form::select('entity_id', $arrEntities, old('id') ?? [], ['class'=>'form-control picker select2', 'id'=>'modality', 'placeholder'=> 'Seleccione la Modalidad']) !!}
                        </div>
                    </div>
                    
                    <div class="col-sm-12">
                        <div class="form-group">
                            {!! Form::label('observation', 'Observaciones:', ['class'=>'form-label']) !!}
                            {!! Form::textarea('observation', null, ['class'=>'form-control', 'rows' => 3, 'placeholder'=>'Escribe la observación de este asunto','required']) !!}
                        </div>
                    </div>

                    <div class="form-group text-end">
                        <label for="files" class="btn btn-icon btn-purple" data-toggle="tooltip" data-placement="top" title="Adjuntar archivos">
                            <i class="mdi mdi-paperclip"></i>
                        </label>
                        <input hidden class="custom-files" type="file" id="files" name="files[]" multiple data-multiple-caption="{count} archivos seleccionados" />
                    </div>
                    
                </div>
            </div>

            <div class="card-footer">
                <div class="text-right">
                    <a href="{{ route('meetings.index') }}" type="button" class="btn btn-default">Regresar</a>
                    {!! Form::submit('Guardar', ['class'=>'btn btn-info']) !!}
                </div>
            </div>
        </div>
        {!! Form::close() !!}
    @endcan

    
    @if(count($arrSubjects) > 0)
        <div class="row p-2">
            <div class="col-12 p-2">
                <div class="card">
                    {!! Form::open(['route'=>['meetings.subject', $meeting_id], 'method' => 'POST', 'autocomplete'=>'off']) !!}
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4 col-sm-12">
                                {!! Form::select('entity_id', $arrEntities, $request->entity_id, ['class'=>'form-control picker select2','placeholder' => '','id'=>'entity_id','data-placeholder' => 'Seleccionar institución']) !!}
                            </div>

                            <div class="col-md-4 col-sm-12">
                                {!! Form::text('subject', $request->subject, ['class'=>'form-control', 'placeholder'=>'Buscar por asunto']) !!}
                            </div>

                            <div class="col-md-4 col-sm-12">
                                {!! Form::text('expositor', $request->expositor, ['class'=>'form-control', 'placeholder'=>'Buscar por expositor']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="text-right">
                            <a href="{{ route('meetings.subject', $meeting_id) }}" type="button" class="btn btn-default">Restablecer</a>
                            {!! Form::submit('Filtrar', ['class'=>'btn btn-info']) !!}
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>

            @foreach ($arrSubjects as $single)
                <div class="col-12 p-2">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-8" style="align-self: center;">
                                    <label style="font-size: 14px; font-weight: 500;">{{ $single->expositor }}</label>
                                </div>
                                <div class="col-4 text-end">
                                    <a data-parent_id="{{$single->id}}" data-mod_id="{{\App\Models\File::MOD_SUBJECTS}}"
                                        class="btn btn-icon btn-purple btn-files-upload" data-toggle="tooltip" data-placement="top" title="Cargar adjuntos"
                                        data-bs-toggle="modal" data-bs-target="#files_upload">
                                            <i class="mdi mdi-paperclip"></i>
                                    </a>

                                    <a data-parent_id="{{$single->id}}" data-mod_id="{{\App\Models\File::MOD_SUBJECTS}}"
                                        class="btn btn-icon btn-default btn-files" data-toggle="tooltip" data-placement="top" title="Ver adjuntos"
                                        data-bs-toggle="modal" data-bs-target="#files_detail">
                                            <i class="mdi mdi-file-search-outline"></i>
                                    </a>

                                    @if(Auth::user()->hasRole(Helper::$roleSuperUsuario))
                                        <a href="{{ route('meetings.edit_subject', $single->id) }}" class="btn btn-icon btn-warning" data-toggle="tooltip" data-placement="top" title="Editar asunto">
                                            <i class="mdi mdi-file-document-edit"></i>
                                        </a>
                                    

                                        <a data-message="¿Está seguro de eliminar este asunto?" 
                                            href="{{ route('meetings.delete_subject', $single->id) }}" 
                                            class="btn btn-icon btn-info btn-confirm" data-toggle="tooltip" data-placement="top" title="Eliminar asunto">
                                            <i class="mdi mdi-trash-can"></i>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">{{$single->subject}}</h5>
                            <p class="card-text">{{$single->entity->name}}</p>
                        </div>
                        @if(!empty($single->observation))
                            <div class="card-footer">
                                <p class="card-text"><b>Observación: </b>{{$single->observation}}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <div class="d-flex justify-content-center">
            {!! $arrSubjects->appends(request()->except(['page','_token']))->render() !!}
        </div>
    @endif
            
    <div class="modal fade" id="files_detail_upload">
        <div class="modal-dialog modal-sm">
            {!! Form::open(['route'=>'meetings.subject_files', 'method' => 'POST', 'id' => 'frmSubjectFiles', 'files' => true]) !!}
                {!! Form::hidden('subject_id', null, array('id' => 'subject_id')) !!}
                {!! Form::hidden('meeting_id', $meeting_id) !!}
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Adjuntar archivos</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div id="">
                            <div class="mb-3">
                                <label for="files" class="form-label">Selecciona archivos adjuntos:</label>
                                <input class="form-control" type="file" id="files" name="files[]" multiple />
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-info" data-bs-dismiss="modal">Adjuntar</button>
                    </div>
                </div>
            {!! Form::close() !!}
        </div>
    </div>


        
@endsection

@push('scripts')
    <script type="text/javascript">
        $('.btn-files-upload').click(function () {
            let parent_id = $(this).data("parent_id");
            $("#subject_id").val(parent_id);
            $("#files_detail_upload").modal("show");
        });
    </script>
@endpush