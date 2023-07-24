@extends('layout.default')

@section('title', 'Editar permiso')
@section('description', 'Modifica el nombre de un permiso')
@section('breadcrumbs')
    {{ Breadcrumbs::render('permissions.edit',$permission) }}
@endsection

@section('content')

    {!! Form::open(['route'=>['permissions.update',$permission], 'method' => 'PUT', 'id' => 'frmPermission']) !!}

    <div class="card">
        <div class="card-body">

            <div class="row g-3">
                <div class="col-sm-6">
                    <div class="form-group">
                        {!! Form::label('name','Nombre',['class'=>'form-label']) !!}
                        {!! Form::text('name', $permission->name, ['class'=>'form-control', 'placeholder'=>'Escribe un nombre para el rol de usuario','required']) !!}
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="form-group">
                        {!! Form::label('name','Grupo',['class'=>'form-label']) !!}
                        {!! Form::select('group_permission_id', $groups, $permission->group_permission_id, ['class'=>'form-control select2', 'data-placeholder'=>'Selecciona un grupo','required']) !!}
                    </div>
                </div>
                <div class="col-sm-4">
                    {!! Form::label('route','Nombre de rutas',['class'=>'form-label']) !!}
                    <div class="form-row">
                        {!! Form::select('route', $routes, $permission->route, ['class'=>'form-control','id'=>'route', 'placeholder'=>'Buscar por rutas']) !!}
                    </div>
                </div>

            </div>
        </div>

        <div class="card-footer">
            <div class="text-right">
                <a href="{{ route('permissions.index') }}" type="button" class="btn btn-default">Regresar</a>
                {!! Form::submit('Editar', ['class'=>'btn btn-info']) !!}
            </div>
        </div>

    </div>

    {!! Form::close() !!}

@endsection

@push("scripts")
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    {!! JsValidator::formRequest('App\Http\Requests\PermissionsStoreRequest', '#frmPermission'); !!}

    <script>
        $(function(){

            $('#route').select2({
                theme: 'bootstrap-5',
                language: 'es',
                placeholder: 'Seleccionar una ruta',
                allowClear: true
            });

        });
    </script>


@endpush
