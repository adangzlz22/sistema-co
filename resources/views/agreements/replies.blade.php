@extends('layout.default')
@section('title', 'Agregar avances')
@section('description', 'Se agregan avances a un acuerdo en específico')

@section('breadcrumbs')
    {{ Breadcrumbs::render('agreements.reply',$model) }}
@endsection

@push('css')
<style>
    .card-text{
        height: 110px;display: -webkit-box;
        -webkit-line-clamp: 5;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>
@endpush

@push('scripts')
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    {!! JsValidator::formRequest('App\Http\Requests\ActionCreateRequest', '#frmAction'); !!}

@endpush

@section('content')

    <div class="row p-2">
        <div class="col-sm-12 p-2">
            <div class="card border-warning">
                <div class="card-header">
                    Reunión: {{$model->meeting->meeting_type->acronym}} {{date("d/m/Y", strtotime($model->meeting->meeting_date))}} {{$model->meeting->meeting_time}} Hrs
                </div>
                <figure class="text-center">
                    <blockquote class="blockquote">
                        <p class="h6">{!! $model->agreement !!}</p>
                    </blockquote>
                    <figcaption class="blockquote-footer">
                        Estatus: <cite title="{{$model->status->name}}">{!! $model->status->name !!}</cite>
                    </figcaption>
                </figure>
                <div class="card-footer">
                    @foreach ($model->entities as $entity)
                    <span class="badge rounded-pill bg-secondary" title="{{$entity->name}}">{{$entity->acronym}}</span>
                    @endforeach
                </div>
            </div>
        </div>
        @can('Crear Acciones')
        <div class="col-sm-12 p-2">
            <div class="accordion accordion-flush" id="accordionFlushExample">
                <div class="accordion-item">
                    <div class="d-flex w-100 justify-content-end">
                        <h2 class="accordion-header" id="flush-headingOne">
                            <button class="btn btn-success btn-sm float-right" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                            <i class="mdi mdi-plus"></i> AGREGAR ACCIÓN
                            </button>
                        </h2>
                    </div>
                    
                  <div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                    <div class="accordion-body">
                        {!! Form::open(['route'=>'agreements.action.store', 'method' => 'POST', 'id' => 'frmAction']) !!}
                            {!! Form::hidden('agreement_id', $agreement_id) !!}
                            <div class="card m-0">
                                <div class="card-body">
                                    <div class="row g-3">
                                        
                                        <div class="col-lg-{{$isAdmin?'6':'12'}} col-sm-12">
                                            <div class="form-group">
                                                {!! Form::label('action', 'Acción:', ['class'=>'form-label']) !!}
                                                {!! Form::textarea('action', null, ['class'=>'form-control', 'rows' => 2, 'placeholder'=>'Escribe la acción a realizar','required']) !!}
                                            </div>
                                        </div>
                                        @if ($isAdmin)
                                            <div class="col-lg-6 col-sm-12">
                                                <div class="form-group">
                                                    {!! Form::label('entity_id', 'Institución:', ['class'=>'form-label']) !!}
                                                    {!! Form::select('entity_id', $arrEntities, null, ['class'=>'form-control picker select2','placeholder' => '','id'=>'entity_id','data-placeholder' => 'Seleccionar institución','required']) !!}
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
        
                                <div class="card-footer">
                                    <div class="text-right">
                                        <a href="{{ route('agreements.index') }}" type="button" class="btn btn-default">Regresar</a>
                                        {!! Form::submit('Guardar', ['class'=>'btn btn-info']) !!}
                                    </div>
                                </div>
                            </div>
                        {!! Form::close() !!}
                    </div>
                  </div>
                  
                </div>
              </div>
        </div>
        @endcan
        <div class="col-sm-12 p-2">
                <div class="card">
                    <div class="card-body">
                    @if(!Auth::user()->hasRole(Helper::$roleEntidad ))
                    {!! Form::open(['route'=>['agreements.reply',$model->id], 'method' => 'POST', 'autocomplete'=>'off']) !!}   
                    <div class="row g-3"> 
                            <div class="col-md-4 col-sm-12">
                                {!! Form::select('entity', $arrEntities, $request->entity, ['class'=>'form-control picker select2','placeholder' => '','id'=>'entity','data-placeholder' => 'Seleccionar institución']) !!}
                            </div>
                            <div class="col-md-3 col-sm-12">
                                <div class="form-group">
                                    {!! Form::select('status_id', $arrStatus, $request->status_id ?? null, ['class'=>'form-control picker select2', 'id'=>'status_id', 'placeholder'=> '' , 'data-placeholder' => 'Seleccione estatus']) !!}
                                </div>
                            </div>
                            <div class="col-md-5 col-sm-12">
                                {!! Form::text('action', $request->action, ['class'=>'form-control', 'placeholder'=>'Buscar por acción']) !!}
                            </div>
                            <div class="card-footer">
                            <div class="text-end">
                                <a href="{{ route('agreements.reply',$model->id) }}" class="btn btn-default">Restablecer</a>
                                {!! Form::submit('Filtrar', ['class'=>'btn btn-info']) !!}
                            </div>
                            </div>
                        </div>
                    {!! Form::close() !!}
                    @endif
                        <div class="row g-3">
                                <h5>Acciones:</h5>
                                @if (sizeof($arrActions)==0)
                                    <p>No se encontraron acciones</p>
                                @endif
                                  @foreach ($arrActions as $action)
                                  <div class="col-lg-4 col-sm-12">
                                    <div class="card shadow-sm">
                                        <div class="card-header">
                                            <div class="row">
                                                <div class="col-6">
                                                    <h6>{{$action->entity->acronym}}</h6>
                                                </div>
                                                <div class="col-6 text-end">
                                                    <span class="badge rounded-pill bg-{{$action->status->color}}">{{$action->status->name}}</span>
                                                </div>
                                            </div>
                                            
                                        </div>
                                        <div class="card-body">
                                            <div class="row g-0">
                                                <div class="col-lg-12 col-sm-12">
                                                    <p class="mb-0 mt-2 fs-14px card-text" data-toggle="tooltip" data-placement="top" title="{{strlen($action->action)>239?$action->action:''}}">
                                                        {{$action->action}}
                                                    </p>
                                                </div>
                                                <div class="col-lg-12 col-sm-12">
                                                    <footer>
                                                        <hr>
                                                        <div class="row">
                                                            <div class="col-lg-12 col-12 text-end">
                                                                @can('Crear Avances')
                                                                    @if ($action->status_id!=\App\Models\Status::CONCLUIDO || $isAdmin)
                                                                        <a data-action_id="{{$action->id}}"
                                                                            class="btn btn-icon btn-purple btn-reply-action" data-toggle="tooltip" data-placement="top" title="Dar avance"
                                                                            data-bs-toggle="modal" data-bs-target="#reply_action">
                                                                                <i class="mdi mdi-message-reply-text-outline"></i>
                                                                        </a>
                                                                    @endif
                                                                @endcan
                                                                @if (sizeof($action->replies)>0)
                                                                    @can('Consultar Avances')
                                                                        <a data-action_id="{{$action->id}}"
                                                                            class="btn btn-icon btn-default btn-replies" data-toggle="tooltip" data-placement="top" title="Ver avances"
                                                                            data-bs-toggle="modal" data-bs-target="#replies_detail">
                                                                                <i class="mdi mdi-text-search"></i>
                                                                        </a>
                                                                    @endcan
                                                                @endif
                                                                @if (sizeof($action->files)>0)
                                                                    <a data-parent_id="{{$action->id}}" data-mod_id="{{\App\Models\File::MOD_ACTIONS}}"
                                                                        class="btn btn-icon btn-default btn-files" data-toggle="tooltip" data-placement="top" title="Ver adjuntos"
                                                                        data-bs-toggle="modal" data-bs-target="#files_detail">
                                                                            <i class="mdi mdi-file-search-outline"></i>
                                                                    </a>
                                                                @endif
                                                                @can('Editar Avances')
                                                                    @if ($action->status_id!=\App\Models\Status::CONCLUIDO || $isAdmin)
                                                                        <a data-action_id="{{$action->id}}"
                                                                            class="btn btn-icon btn-warning btn-edit-action" data-toggle="tooltip" data-placement="top" title="Editar acción"
                                                                            data-bs-toggle="modal" data-bs-target="#edit_action">
                                                                                <i class="mdi mdi-file-document-edit-outline"></i>
                                                                        </a>
                                                                    @endif
                                                                @endcan
                                                                @can('Eliminar Acciones')
                                                                    @if($action->status_id != 2)
                                                                        <a data-message="¿Está seguro de eliminar este registro?"
                                                                            href="{{ route('actions.destroy',$action->id) }}" class="btn btn-icon btn-info btn-confirm" data-toggle="tooltip" title="Eliminar">
                                                                            <i class="mdi mdi-trash-can-outline" ></i>
                                                                        </a>
                                                                    @endif
                                                                @endcan
                                                            </div>
                                                        </div>
                                                    </footer>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            <div class="col-sm-12">
                            </div>
                            <div class="col-sm-12">
                                <span class="badge rounded-pill bg-warning fs-12px"></span>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    </div>

    <div class="modal fade" id="replies_detail">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Avances</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="replies_detail_content">
    
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="reply_action">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Dar avance</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="reply_action_content">
    
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="edit_action">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Editar acción</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="edit_action_content">
    
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
<script>
$('.btn-reply-action').click(function () {
    LoadigShow();
    $.post("{{ route('agreements.reply.action') }}", { action_id: $(this).data("action_id") })
        .done(function (data) {
            $('#reply_action_content').html(data);
        }).fail(function (data) {
        console.log(data);
    }).always(function () {
        LoadingHide(300);
    });
});

$('.btn-replies').click(function () {
    LoadigShow();
    $.post("{{ route('agreements.action.replies') }}", { action_id: $(this).data("action_id") })
        .done(function (data) {
            $('#replies_detail_content').html(data);
        }).fail(function (data) {
        console.log(data);
    }).always(function () {
        LoadingHide(300);
    });
});

$('.btn-edit-action').click(function () {
    LoadigShow();
    $.post("{{ route('agreements.action.edit') }}", { action_id: $(this).data("action_id") })
        .done(function (data) {
            $('#edit_action_content').html(data);
        }).fail(function (data) {
        console.log(data);
    }).always(function () {
        LoadingHide(300);
    });
});
</script>
@endpush


