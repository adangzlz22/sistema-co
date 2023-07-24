@extends('layout.default')

@section('title', 'Lugares')
@section('description', 'Ver listado')

@section('breadcrumbs')
    {{ Breadcrumbs::render('places.index') }}
@endsection

@section('title-action')
    @can('Crear Lugares')
        <a class="btn btn-success btn-sm float-right" href="{{ route('places.create') }}" title="Crear nuevo lugar">
            <span class="mdi mdi-plus"></span> NUEVO LUGAR
        </a>
    @endcan
@endsection

@section('content')

            <div class="card mb-3">
                {!! Form::open(['route'=>'places.index', 'method' => 'POST', 'autocomplete'=>'off']) !!}
                <div class="card-body">
                    <div class="row g-3">

                        <div class="col-12 col-sm-2">
                            {!! Form::text('name', $request->name, ['class'=>'form-control', 'placeholder'=>'Buscar por nombre']) !!}
                        </div>

                        <div class="col-12 col-sm-4">
                            {!! Form::text('address', $request->address, ['class'=>'form-control', 'placeholder'=>'Buscar por dirección']) !!}
                        </div>
                    </div>
                </div>    
                <div class="card-footer">
                    <div class="text-right">
                        <a href="{{ route('places.index') }}" class="btn btn-default">Restablecer</a>
                        {!! Form::submit('Filtrar', ['class'=>'btn btn-info']) !!}
                    </div>
                </div>
                {!! Form::close() !!}
            </div>

            <div class="card card-body">
                <table class="table table-striped table-sm">
                    <thead>
                        <th>Nombre</th>
                        <th>Dirección</th>
                        <th>Estatus</th>
                        <th>Acciones</th>
                    </thead>
                    <tbody>
                    @foreach ($arrPlaces as $single)
                        <tr>
                            <td>{{ $single->name }}</td>
                            <td>{{ $single->address }}</td>
                            <td><span class="badge bg-{{($single->status_id == 1 ? 'success': 'danger' )}} text-white rounded-sm fs-12px font-weight-500">{{$single->status->name}}</span></td>
                            <td>
                                @can('Editar Lugares')
                                    <a href="{{ route('places.edit', $single->id) }}" class="btn btn-icon btn-warning">
                                        <i class="mdi mdi-file-document-edit" title="Editar"></i>
                                    </a>
                                @endcan

                                @can('Eliminar Lugares')
                                    @if($single->status_id == 1)
                                        <a data-message="¿Está seguro de eliminar este registro?"
                                            href="{{ route('places.destroy',$single->id) }}" class="btn btn-icon btn-danger btn-confirm">
                                            <i class="mdi mdi-trash-can" title="Eliminar"></i>
                                        </a>
                                    @endif
                                @endcan

                                @can('Eliminar Lugares')
                                    @if($single->status_id == 2)
                                        <a data-message="¿Está seguro de activar este registro?"
                                            href="{{ route('places.active',$single->id) }}" class="btn btn-icon btn-secondary btn-confirm" title="Activar">
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
                {!! $arrPlaces->appends(request()->except(['page','_token']))->render() !!}
            </div>

@endsection
