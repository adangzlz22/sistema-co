@extends('layout.default')



@section('title', 'Instituciones')
@section('description', 'Ver listado')


@section('breadcrumbs')
    {{ Breadcrumbs::render('entities.index') }}
@endsection
@section('title-action')

    @can('Crear Institucion')
        <a class="btn btn-success btn-sm float-right" href="{{ route('entities.create') }}" title="Crear nueva institución">
            <span class="mdi mdi-plus"></span> NUEVA INSTITUCIÓN
        </a>
    @endcan

@endsection


@section('content')

            <div class="card card-body mb-3">
                {!! Form::open(['route'=>'entities.index', 'method' => 'POST', 'autocomplete'=>'off']) !!}
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-2 col-sm-12">
                            {!! Form::text('acronym', $request->acronym, ['class'=>'form-control', 'placeholder'=>'Buscar por siglas']) !!}
                        </div>

                        <div class="col-md-4 col-sm-12">
                            {!! Form::text('name', $request->name, ['class'=>'form-control', 'placeholder'=>'Buscar por nombre']) !!}
                        </div>

                        <div class="col-md-3 col-sm-12">
                            <div class="form-row">
                                {!! Form::select('entities_type', $arrEntitiesTypes, $request->entities_type ?? [], ['class'=>'form-control picker select2', 'id'=>'entities_type', 'placeholder'=> '', 'data-placeholder' => 'Seleccione tipo institución']) !!}
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-12">
                            <div class="form-group">
                                {!! Form::select('meeting_type', $arrMeetingTypes, $request->meeting_type ?? [], ['class'=>'form-control picker select2', 'id'=>'meeting_type', 'placeholder'=> '' , 'data-placeholder' => 'Seleccione tipo de reunión']) !!}
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card-footer">
                    <div class="text-right">
                        <a href="{{ route('entities.index') }}" class="btn btn-default">Restablecer</a>
                        {!! Form::submit('Filtrar', ['class'=>'btn btn-info']) !!}
                    </div>
                </div>
                {!! Form::close() !!}
            </div>

            <div class="card card-body">
                <table class="table table-striped table-sm">
                    <thead>
                        <th>Siglas</th>
                        <th>Nombre</th>
                        <th>Contacto</th>
                        <th>Tipo reunión</th>
                        <th>Tipo institución</th>
                        <th>Estatus</th>
                        <th>Acciones</th>
                    </thead>
                    <tbody>
                    @foreach ($arrEntities as $single)
                        <tr>
                            <td>{{ $single->acronym }}</td>
                            <td>
                                <p>{{ $single->name }}</p>
                                <small>
                                    {{ $single->holder }}<br />
                                    {{ $single->job }}
                                </small>
                            </td>
                            <td>
                                <p>{{ $single->phone }}</p>
                                {{ $single->email }}
                            </td>
                            <td style="width: 100px;">
                                @foreach ($single->meeting_types as $type)
                                <span class="badge rounded-pill bg-secondary">{{$type->name}}</span>
                                @endforeach
                            </td>
                            <td><span class="badge bg-success text-white rounded-sm fs-12px font-weight-500">{{ $single->entities_type->name }}</span></td>
                            <td><span class="badge bg-{{($single->status_id == 1 ? 'success': 'danger' )}} text-white rounded-sm fs-12px font-weight-500">{{ $single->status->name }}</span></td>
                            <td>
                                @can('Editar Tipos de Organismos')
                                    <a href="{{ route('entities.edit', $single->id) }}" class="btn btn-icon btn-warning">
                                        <i class="mdi mdi-file-document-edit" title="Editar"></i>
                                    </a>
                                @endcan

                                @can('Editar Tipos de Organismos')
                                    @if($single->status_id == 1)
                                        <a data-message="¿Está seguro de eliminar este registro?"
                                            href="{{ route('entities.destroy',$single->id) }}" class="btn btn-icon btn-danger btn-confirm">
                                            <i class="mdi mdi-trash-can" title="Eliminar"></i>
                                        </a>
                                    @endif
                                @endcan

                                @can('Eliminar Tipos de Organismos')
                                    @if($single->status_id == 2)
                                        <a data-message="¿Está seguro de activar este registro?"
                                            href="{{ route('entities.active',$single->id) }}" class="btn btn-icon btn-secondary btn-confirm" title="Activar">
                                            <i class="mdi mdi-checkbox-multiple-marked-circle-outline"></i>
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
                {!! $arrEntities->appends(request()->except(['page','_token']))->render() !!}
            </div>

@endsection
