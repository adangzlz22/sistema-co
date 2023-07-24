@extends('layout.default')


@section('title', 'Permisos de usuario')
@section('description', 'Gestiona los permisos de acceso a las diferentes opciones del sistema')
@section('breadcrumbs')
    {{ Breadcrumbs::render('permissions.index') }}
@endsection


@section('title-action')
    @can('Crear Permisos')
        <a class="btn btn-success btn-sm float-right" href="{{ route('permissions.create') }}"
           title="Crear nuevo permiso de usuario">
            <span class="mdi mdi-plus"></span> NUEVO PERMISO
        </a>
    @endcan
@endsection



@section('content')
    <div class="card card-body">
        <table class="table table-striped">
            <thead>
            <tr>
                <th class="col-8">Nombre</th>
                <th class="col-2">Grupo</th>
                <th class="col-2 text-center">Acciones</th>
            </tr>
            </thead>
            <tbody>
            @foreach($permissions as $permission)
                <tr>
                    <td>{{$permission->name}}</td>
                    <td>{{$permission->group_permission->name}}</td>
                    <td class="text-center">

                        @can('Editar Permisos')
                        <a href="{{ route('permissions.edit', $permission->id) }}" class="btn btn-icon btn-warning">
                            <i class="mdi mdi-file-document-edit" title="Editar"></i>
                        </a>
                        @endcan

                        @can('Eliminar Permisos')
                            <a data-message="¿Está seguro de eliminar este registro?"
                               href="{{ route('permissions.destroy',$permission->id) }}"
                               class="btn btn-icon btn-danger btn-confirm">
                                <i class="mdi mdi-trash-can" title="Eliminar"></i>
                            </a>
                        @endcan


                    </td>
                </tr>

            @endforeach
            </tbody>
        </table>


        <div class="d-flex justify-content-center">
            {!! $permissions->appends(request()->except(['page','_token']))->render() !!}
        </div>
    </div>
@endsection
