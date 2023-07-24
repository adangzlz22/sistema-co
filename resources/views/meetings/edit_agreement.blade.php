@extends('layout.default')
@section('title', 'Editar acuerdos')
@section('description', 'Actualiza un acuerdo en específico')

@section('breadcrumbs')
    {{ Breadcrumbs::render('meetings.edit_agreement', $model[0]->meeting) }}
@endsection

@push('css')

@endpush

@push('scripts')
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    {!! JsValidator::formRequest('App\Http\Requests\AgreementsCreateRequest', '#frmAgreement'); !!}
@endpush

@section('content')

    {!! Form::open(['route'=>['meetings.update_agreement', $model[0]], 'method' => 'PUT', 'autocomplete'=>'off']) !!}
        {!! Form::hidden('meeting_id', $model[0]->meeting_id ) !!}
        <div class="card">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-sm-12">
                        <div class="form-group">
                            {!! Form::label('agreement', 'Acuerdo:', ['class'=>'form-label']) !!}
                            {!! Form::textarea('agreement', $model[0]->agreement, ['class'=>'form-control', 'rows' => 3, 'placeholder'=>'Escribe el acuerdo','required']) !!}
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
                                                    <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault" />
                                                    {!! Form::checkbox('entity_id[]', $key['id'],  (in_array($key['id'], $arrSelectedEntities, true) ? true : false), ['class'=>'form-check-input','id'=>'chbx-' . $key['id']]) !!}
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
                                                    <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault" />
                                                    {!! Form::checkbox('entity_id[]', $key['id'], (in_array($key['id'], $arrSelectedEntities, true) ? true : false), ['class'=>'form-check-input','id'=>'chbx-' . $key['id']]) !!}
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
                                                    <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault" />
                                                    {!! Form::checkbox('entity_id[]', $key['id'],  (in_array($key['id'], $arrSelectedEntities, true) ? true : false), ['class'=>'form-check-input','id'=>'chbx-' . $key['id']]) !!}
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
                                                    <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault" />
                                                    {!! Form::checkbox('entity_id[]', $key['id'],  (in_array($key['id'], $arrSelectedEntities, true) ? true : false), ['class'=>'form-check-input','id'=>'chbx-' . $key['id']]) !!}
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
                    <a href="{{ route('meetings.agreement', $model[0]->meeting_id) }}" type="button" class="btn btn-default">Regresar</a>
                    {!! Form::submit('Editar', ['class'=>'btn btn-info']) !!}
                </div>
            </div>
        </div>
    {!! Form::close() !!}

@endsection


