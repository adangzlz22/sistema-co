@extends('layout.default')


@section('title', 'Roles de acceso')
@section('description', 'Administra los roles de acceso de los usuarios del sistema')

@section('breadcrumbs')
    {{ Breadcrumbs::render('roles.index') }}
@endsection


@section('title-action')
    @can('Crear Roles')
        <a class="btn btn-success btn-sm float-right" href="{{ route('roles.create') }}" title="Crear nuevo rol de usuario">
            <span class="mdi mdi-plus"></span> NUEVO ROL
        </a>
    @endcan
@endsection



@section('content')

    <div class="card card-body">

        <table class="table table-striped">
            <thead>
            <tr>
                <th>Nombre</th>
                <th class="col-sm-2">Acciones</th>
            </tr>
            </thead>
            <tbody>
            @foreach($roles as $role)
                <tr>
                    <td>{{$role->name}}</td>
                    <td>

                        @can('Asignar Permisos')
                            <a href="{{ route('roles.permissions', $role->id) }}" class="btn btn-icon btn-purple">
                                <i class="mdi mdi-lock-open-outline" title="Asignar permisos"></i>
                            </a>
                        @endcan

                        @can('Editar Roles')
                            <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-icon btn-warning">
                                <i class="mdi mdi-file-document-edit" title="Editar"></i>
                            </a>
                        @endcan

                        @can('Eliminar Roles')
                            <a data-message="¿Está seguro de eliminar este registro?"
                               href="{{ route('roles.destroy',$role->id) }}" class="btn btn-icon btn-danger btn-confirm">
                                <i class="mdi mdi-trash-can" title="Eliminar"></i>
                            </a>
                        @endcan


                    </td>
                </tr>

            @endforeach
            </tbody>
        </table>


        <div class="d-flex justify-content-center">
            {!! $roles->appends(request()->except(['page','_token']))->render() !!}
        </div>

    </div>



@endsection
