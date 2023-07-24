@extends('layout.default')



@section('title', 'Reuniones')
@section('description', 'Ver listado')


@section('breadcrumbs')
    {{ Breadcrumbs::render('meetings.index') }}
@endsection
@section('title-action')

    @can('Crear Reunion')
        <a class="btn btn-success btn-sm float-right" href="{{ route('meetings.create') }}" title="Crear una nueva reunión">
            <span class="mdi mdi-plus"></span> NUEVA REUNIÓN
        </a>
    @endcan

@endsection


@section('content')

    <div class="card mb-3">
        {!! Form::open(['route'=>'meetings.index', 'method' => 'POST', 'autocomplete'=>'off']) !!}
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-2 col-sm-12">
                    {!! Form::text('folio', $request->folio, ['class'=>'form-control', 'placeholder'=>'Buscar por folio']) !!}
                </div>

                @if(!Auth::user()->hasRole(Helper::$roleEntidad ))
                    <div class="col-md-3 col-sm-12">
                        {!! Form::select('meeting_type_id', $arrMeetingTypes, $request->meeting_type_id, ['class'=>'form-control select2','placeholder' => '','id'=>'meeting_type_id','data-placeholder' => 'Seleccionar tipo de reunión']) !!}
                    </div>
                @else
                    <div class="col-md-3 col-sm-12">
                        {!! Form::select('meeting_type_id', Auth::user()->entity->meeting_types->pluck('name', 'id'), $request->meeting_type_id, ['class'=>'form-control select2','placeholder' => '','id'=>'meeting_type_id','data-placeholder' => 'Tipo de reunión']) !!}
                    </div>
                @endif

                <div class="col-md-5 col-sm-12">
                    <div class="input-group input-daterange datepicker p-0" id="datepicker">
                        <span class="input-group-text">Fecha</span>
                        <input type="text" class="form-control " name="init_date" placeholder="Desde" value="{{ $request->init_date ?? '' }}"  />
                        <span class="input-group-text">a</span>
                        <input type="text" class="form-control" name="end_date" placeholder="Hasta" value="{{ $request->end_date ?? '' }}"  />
                    </div>
                </div>

                <div class="col-md-2 col-sm-12">
                    <div class="form-group">
                        {!! Form::select('status_id', $arrStatus, $request->status_id, ['class'=>'form-control picker select2', 'id'=>'status_id', 'placeholder'=> '', 'data-placeholder' => 'Estatus de la reunión']) !!}
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <div class="text-right">
                <a href="{{ route('meetings.index') }}" type="button" class="btn btn-default">Restablecer</a>
                {!! Form::submit('Filtrar', ['class'=>'btn btn-info']) !!}
            </div>
        </div>
        {!! Form::close() !!}
    </div>

    <div class="card card-body">
        <table class="table table-striped table-sm">
            <thead>
                <th>Folio</th>
                <th>Nombre</th>
                <th>Fecha</th>
                <th>Hora</th>
                <th>Lugar / Link</th>
                <th>Estatus</th>
                <th>Acciones</th>
            </thead>
            <tbody>
            @foreach ($arrMeetings as $single)
                <tr>
                    <td>{{ $single->folio }}</td>
                    <td>{{ $single->meeting_type->name }}</td>
                    <td>{{ date("d/m/Y", strtotime($single->meeting_date))}}</td>
                    <td>{{ $single->meeting_time }} Hrs.</td>
                    <td>{!! ($single->modality_id == 1 ? $single->place->name . '<br />' .  $single->place->address: ($single->modality_id == 2 ? $single->link: $single->place->name . '<br />' .  $single->place->address . "<br />Link: " . $single->link)) !!}</td>
                    <td><span class="badge bg-{{$single->status->color}} text-white rounded-sm fs-12px font-weight-500">{{$single->status->name}}</span></td>
                    <td>
                        @can('Consultar Asuntos')
                            @if ($single->status->id == \App\Models\Status::POR_CELEBRAR)
                                <a href="{{ route('meetings.subject', $single->id) }}" class="btn btn-icon btn-purple" title="Asuntos">
                                    <i class="mdi mdi-message-text"></i>
                                </a>
                            @endif
                        @endcan

                        @can('Consultar Acuerdos')
                            @if ($single->status->id != \App\Models\Status::CANCELADA)
                                <a href="{{ route('meetings.agreement', $single->id) }}" class="btn btn-icon btn-default" title="Acuerdos">
                                    <i class="mdi mdi-handshake-outline"></i>
                                </a>
                            @endcan
                        @endcan

                        @can('Editar Reunion')
                            @if($single->status->id==\App\Models\Status::POR_CELEBRAR)
                                <a href="{{ route('meetings.edit', $single->id) }}" class="btn btn-icon btn-warning">
                                    <i class="mdi mdi-file-document-edit" title="Editar reunión"></i>
                                </a>
                            @endif
                        @endcan

                        @can('Cancelar Reunion')
                            @if($single->status->id==\App\Models\Status::POR_CELEBRAR)
                                <a data-message="¿Está seguro de cancelar esta reunión?"
                                    href="{{ route('meetings.cancel', $single->id) }}" class="btn btn-icon btn-info btn-confirm">
                                    <i class="mdi mdi-trash-can" title="Cancelar reunión "></i>
                                </a>
                            @endif
                        @endcan
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-center">
        {!! $arrMeetings->appends(request()->except(['page','_token']))->render() !!}
    </div>

@endsection
