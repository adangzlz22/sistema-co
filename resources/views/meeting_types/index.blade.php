@extends('layout.default')



@section('title', 'Tipo de reuniones')
@section('description', 'Ver listado')


@section('breadcrumbs')
    {{ Breadcrumbs::render('meeting_types.index') }}
@endsection
@section('title-action')

    @can('Crear Tipos de Reuniones')
        <a class="btn btn-success btn-sm float-right" href="{{ route('meeting_types.create') }}" title="Crear nuevo tipo de reunión">
            <span class="mdi mdi-plus"></span> NUEVO TIPO DE REUNIÓN
        </a>
    @endcan

@endsection


@section('content')


            <div class="card card-body mb-3">
                {!! Form::open(['route'=>'meeting_types.index', 'method' => 'POST', 'autocomplete'=>'off']) !!}
                <div class="row g-3">
                    <div class="col-12 col-sm-4">
                        {!! Form::text('acronym', $request->acronym, ['class'=>'form-control', 'placeholder'=>'Buscar por siglas']) !!}
                    </div>
                    <div class="col-12 col-sm-4">
                        {!! Form::text('name', $request->name, ['class'=>'form-control', 'placeholder'=>'Buscar por nombre']) !!}
                    </div>

                    <div class="col-12 col-sm-4 text-end">
                        <a href="{{ route('meeting_types.index') }}" class="btn btn-default">Restablecer</a>
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
                <th>Estatus</th>
                <th>Acciones</th>
                </thead>
                <tbody>
                @foreach ($meeting_types as $meeting)
                    <tr>
                        <td>{{ $meeting->acronym }}</td>
                        <td>{{ $meeting->name }}</td>
                        <td><span class="badge bg-{{($meeting->status_id == 1 ? 'success': 'danger' )}} text-white rounded-sm fs-12px font-weight-500">{{$meeting->status->name}}</span></td>
                        <td>
                            @can('Editar Tipos de Reuniones')
                                <a href="{{ route('meeting_types.edit', $meeting->id) }}" class="btn btn-icon btn-warning">
                                    <i class="mdi mdi-file-document-edit" title="Editar"></i>
                                </a>
                            @endcan

                            @can('Eliminar Tipos de Reuniones')
                                @if($meeting->status_id == 1)
                                    <a data-message="¿Está seguro de eliminar este registro?"
                                        href="{{ route('meeting_types.destroy', $meeting->id) }}" class="btn btn-icon btn-danger btn-confirm" title="Eliminar">
                                        <i class="mdi mdi-trash-can"></i>
                                    </a>
                                @endif
                            @endcan

                            @can('Eliminar Tipos de Reuniones')
                                @if($meeting->status_id == 2)
                                    <a data-message="¿Está seguro de activar este registro?"
                                        href="{{ route('meeting_types.active', $meeting->id) }}" class="btn btn-icon btn-secondary btn-confirm" title="Activar">
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
                {!! $meeting_types->appends(request()->except(['page','_token']))->render() !!}
            </div>

@endsection
