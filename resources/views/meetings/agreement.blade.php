@extends('layout.default')
@section('title', 'Agregar acuerdos')
@section('description', 'Se agregan acuerdos a una reunión en específico')

@section('breadcrumbs')
    {{ Breadcrumbs::render('meetings.agreement', $model) }}
@endsection

@push('css')

@endpush

@push('scripts')
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    {!! JsValidator::formRequest('App\Http\Requests\AgreementsCreateRequest', '#frmAgreement'); !!}
@endpush

@section('content')

    <div class="row p-2">
        <div class="col-sm-12 p-2">
            <div class="card border-warning mb-3">
                <div class="card-header">
                    FOLIO: {!! $model[0]->folio !!}
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

    @can('Crear Acuerdos')
        {!! Form::open(['route'=>'meetings.agreement_store', 'method' => 'POST', 'id' => 'frmAgreement']) !!}
            {!! Form::hidden('meeting_id', $meeting_id) !!}
            <div class="card">
                <div class="card-body">
                    <div class="row g-3">
                        
                        <div class="col-sm-12">
                            <div class="form-group">
                                {!! Form::label('agreement', 'Acuerdo:', ['class'=>'form-label']) !!}
                                {!! Form::textarea('agreement', null, ['class'=>'form-control', 'rows' => 3, 'placeholder'=>'Escribe el acuerdo','required']) !!}
                            </div>
                        </div>

                        <div class="col-sm-12">
                            {!! Form::label('', 'Instituciones:', ['class'=>'form-label']) !!}
                            <p>
                                <a href="#" class="seleccionar">Todos</a> / 
                                <a href="#" class="deseleccionar">Ninguno</a> / 
                                @if(count($arrEntitiesDep) > 0)
                                    <a href="#" class="seleccionar_d">Dependecias</a> / 
                                @endif

                                @if(count($arrEntitiesEnt) > 0)
                                    <a href="#" class="seleccionar_e">Entidades</a> /
                                @endif

                                @if(count($arrEntitiesFid) > 0)
                                    <a href="#" class="seleccionar_f">Fideicomisos</a> / 
                                @endif

                                @if(count($arrEntitiesOrg) > 0)
                                    <a href="#" class="seleccionar_o">Organismos</a>
                                @endif
                            </p>
                            
                            <div class="row">
                                @if(count($arrEntitiesDep) > 0)
                                    <div class="col-md-3 col-sm-12 " >
                                        {!! Form::label('', 'Dependencia:', ['class'=>'form-label']) !!}
                                        <ul class="list-group list-group-light list-group list-group-flush entities" id="dependencia">
                                            @foreach($arrEntitiesDep as $key)
                                                <li class="list-group-item border-0">
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault">
                                                        {!! Form::checkbox('entity_id[]', $key['id'],  null, ['class'=>'form-check-input','id'=>'chbx-' . $key['id']]) !!}
                                                        <label class="form-check-label" for="chbx-{{ $key['id'] }}" data-toggle="tooltip" data-placement="top" title="{{ $key['name'] }}"> {{ $key['acronym'] }}</label>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                @if(count($arrEntitiesEnt) > 0)
                                    <div class="col-md-3 col-sm-12">
                                        {!! Form::label('', 'Entidad:', ['class'=>'form-label']) !!}
                                        <ul class="list-group list-group-light list-group list-group-flush entities" id="entidad">
                                            @foreach($arrEntitiesEnt as $key)
                                                <li class="list-group-item border-0">
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault">
                                                        {!! Form::checkbox('entity_id[]', $key['id'],  null, ['class'=>'form-check-input','id'=>'chbx-' . $key['id']]) !!}
                                                        <label class="form-check-label" for="chbx-{{ $key['id'] }}" data-toggle="tooltip" data-placement="top" title="{{ $key['name'] }}"> {{ $key['acronym'] }}</label>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                @if(count($arrEntitiesFid) > 0)
                                    <div class="col-md-3 col-sm-12 " >
                                        {!! Form::label('', 'Fideicomiso:', ['class'=>'form-label']) !!}
                                        <ul class="list-group list-group-light list-group list-group-flush entities" id="fideicomiso">
                                            @foreach($arrEntitiesFid as $key)
                                                <li class="list-group-item border-0">
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault">
                                                        {!! Form::checkbox('entity_id[]', $key['id'],  null, ['class'=>'form-check-input','id'=>'chbx-' . $key['id']]) !!}
                                                        <label class="form-check-label" for="chbx-{{ $key['id'] }}" data-toggle="tooltip" data-placement="top" title="{{ $key['name'] }}"> {{ $key['acronym'] }}</label>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                @if(count($arrEntitiesOrg) > 0)
                                    <div class="col-md-3 col-sm-12">
                                        {!! Form::label('', 'Organismo:', ['class'=>'form-label']) !!}
                                        <ul class="list-group list-group-light list-group list-group-flush entities" id="organismo">
                                            @foreach($arrEntitiesOrg as $key)
                                                <li class="list-group-item border-0">
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault">
                                                        {!! Form::checkbox('entity_id[]', $key['id'],  null, ['class'=>'form-check-input','id'=>'chbx-' . $key['id']]) !!}
                                                        <label class="form-check-label" for="chbx-{{ $key['id'] }}" data-toggle="tooltip" data-placement="top" title="{{ $key['name'] }}"> {{ $key['acronym'] }}</label>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            </div>
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


    <div class="row p-2">
        <div class="col-12 p-2">
            <div class="card">
                {!! Form::open(['route'=>['meetings.agreement', $meeting_id], 'method' => 'POST', 'autocomplete'=>'off']) !!}
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-3 col-sm-12">
                                {!! Form::text('agreement', $request->agreement, ['class'=>'form-control', 'placeholder'=>'Buscar por acuerdo']) !!}
                            </div>

                            <div class="col-md-4 col-sm-12">
                                {!! Form::select('entity_id', $arrEntities, $request->entity_id, ['class'=>'form-control picker select2','placeholder' => '','id'=>'entity_id','data-placeholder' => 'Seleccionar institución']) !!}
                            </div>

                            

                            <div class="col-md-2 col-sm-12">
                                <div class="form-group">
                                    {!! Form::select('status_id', $arrStatus, $request->status_id ?? [], ['class'=>'form-control picker select2', 'id'=>'status_id', 'placeholder'=> '' , 'data-placeholder' => 'Seleccione estatus']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="text-right">
                            <a href="{{ route('meetings.agreement', $meeting_id) }}" type="button" class="btn btn-default">Restablecer</a>
                            {!! Form::submit('Filtrar', ['class'=>'btn btn-info']) !!}
                        </div>
                    </div>
                {!! Form::close() !!}
            </div>
        </div>

        @foreach ($arrAgreements as $key => $single)
            <div class="col-sm-12 p-2">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-8">
                                Acuerdo #{{$single->id}} {{ $single->created_at->format('d/m/Y') }}
                                <span class="badge bg-primary">{{$single->status->name}}</span>
                            </div>
                            <div class="col-4 text-end">
                                @if(Auth::user()->hasRole(Helper::$roleSuperUsuario))
                                    <a href="{{ route('meetings.edit_agreement', $single->id) }}" class="btn btn-icon btn-warning" data-toggle="tooltip" data-placement="top" title="Editar acuerdo">
                                        <i class="mdi mdi-file-document-edit"></i>
                                    </a>

                                    <a data-message="¿Está seguro de eliminar este acuerdo?" 
                                        href="{{ route('meetings.delete_agreement', $single->id) }}" 
                                        class="btn btn-icon btn-info btn-confirm" data-toggle="tooltip" data-placement="top" title="Eliminar Acuerdo">
                                        <i class="mdi mdi-trash-can"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">{{$single->agreement}}</h5>
                        <p class="card-text"> 
                            @foreach ($single->entities as $single_entity)
                                <span class="badge bg-secondary" data-toggle="tooltip" data-placement="top" title="{{ $single_entity->name }}">{{ $single_entity->acronym }}</span>
                            @endforeach
                        </p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="d-flex justify-content-center">
        {!! $arrAgreements->appends(request()->except(['page','_token']))->render() !!}
    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function () {
            $('.seleccionar').click(function (e) {
                e.preventDefault();
                $(".entities").find('input:checkbox').prop("checked", true);
            });

            $('.deseleccionar').click(function (e) {
                e.preventDefault();
                $(".entities").find('input:checkbox').prop("checked", false);
            });

            $('.seleccionar_d').click(function (e) {
                e.preventDefault();
                $("#dependencia").find('input:checkbox').prop("checked", true);
                $("#entidad").find('input:checkbox').prop("checked", false);
                $("#fideicomiso").find('input:checkbox').prop("checked", false);
                $("#organismo").find('input:checkbox').prop("checked", false);
            });

            $('.seleccionar_e').click(function (e) {
                e.preventDefault();
                $("#dependencia").find('input:checkbox').prop("checked", false);
                $("#entidad").find('input:checkbox').prop("checked", true);
                $("#fideicomiso").find('input:checkbox').prop("checked", false);
                $("#organismo").find('input:checkbox').prop("checked", false);
            });

            $('.seleccionar_f').click(function (e) {
                e.preventDefault();
                $("#dependencia").find('input:checkbox').prop("checked", false);
                $("#entidad").find('input:checkbox').prop("checked", false);
                $("#fideicomiso").find('input:checkbox').prop("checked", true);
                $("#organismo").find('input:checkbox').prop("checked", false);
            });

            $('.seleccionar_o').click(function (e) {
                e.preventDefault();
                $("#dependencia").find('input:checkbox').prop("checked", false);
                $("#entidad").find('input:checkbox').prop("checked", false);
                $("#fideicomiso").find('input:checkbox').prop("checked", false);
                $("#organismo").find('input:checkbox').prop("checked", true);
            });
        });
    </script>
@endpush