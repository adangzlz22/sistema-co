@extends('layout.default')


@section('title', 'Usuarios')
@section('description', 'Administra el catálogo de usuarios')

@section('breadcrumbs')
    {{ Breadcrumbs::render('users.index') }}
@endsection

@section('title-action')

    @can('Crear Usuarios')
        <a class="btn btn-success btn-sm float-right" href="{{ route('users.create') }}" title="Crear nuevo usuario">
            <span class="mdi mdi-plus"></span> NUEVO USUARIO
        </a>
    @endcan
    {{--{{ __('Se cancelo el acuerdo con folio :folio', ['folio' => '00023']) }}--}}

@endsection


@push('css')

@endpush

@push('scripts')
    <script defer src="{{ asset('js/users.js') }}"></script>
@endpush

@section('content')


            <div class="card card-body mb-3">
                {!! Form::open(['route'=>'users.index', 'method' => 'POST', 'autocomplete'=>'off']) !!}
                <div class="row g-3">

                    @if(Auth::user()->hasRole(Helper::$roleSuperUsuario ))
                        <div class="col-6 col-sm-3">
                            {!! Form::select('entity_id', $entities, $request->entity_id, ['class' => 'form-control select2', 'placeholder' => '', 'data-placeholder' => 'Seleccionar institución', 'id' => 'entity_id']) !!}
                        </div>
                    @endif
                    <div class="col-12 col-sm-3">
                        {!! Form::text('name', $request->name, ['class'=>'form-control', 'placeholder'=>'Buscar por nombre o correo electrónico']) !!}
                    </div>


                    <div class="col-6 col-sm-3 text-center">
                        {!! Form::select('role', $roles,$request->role, ['class'=>'form-control picker']) !!}
                    </div>

                    {{--        <div class="col-6 col-sm-3 text-center">
                                <div class="form-group">
                                    {!! Form::select('organism', $organisms,$request->organism, ['class'=>'form-control']) !!}
                                </div>
                            </div>--}}

                    <div class="col-12 col-sm-3 text-end">
                        <a href="{{ route('users.index') }}" class="btn btn-default">Restablecer</a>
                        {!! Form::submit('Filtrar', ['class'=>'btn btn-info']) !!}
                    </div>

                </div>
                {!! Form::close() !!}
            </div>


            <div class="card card-body">
                <table class="table table-striped table-sm">
                <thead>
                <th></th>
                <th>Nombre</th>
                <th>Correo electrónico</th>
                <th>Roles</th>
                <th class="col-sm-1">Acciones</th>
                </thead>
                <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>
                            <div class="position-relative">

                                @if($user->email_verified_at == null)
                                    <a href="{{ route('users.verify',[$user->id]) }}" title="Verificar usuario" >
                                @endif

                                        <img style="width: 2rem" src="{{ asset('images/avatars/'.$user->avatar) }}"
                                             class="rounded-circle">

                                        @if($user->active != null)
                                            <span class="mdi mdi-circle text-success position-absolute top right small" title="Activo"></span>
                                        @else
                                            <span class="mdi mdi-circle text-danger position-absolute top right small" title="Inactivo"></span>
                                        @endif


                                @if($user->email_verified_at == null)
                                    </a>
                                @endif


                            </div>



                        </td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <ul class="m-0 p-0 list-unstyled">
                                @foreach($user->roles as $role)
                                    <li>
                                        {{ $role->name }}
                                    </li>
                                @endforeach
                            </ul>
                        </td>
                        <td>
                            @can('Editar Usuarios')
                                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-icon btn-warning">
                                    <i class="mdi mdi-file-document-edit" title="Editar"></i>
                                </a>
                            @endcan

                            @can('Eliminar Usuarios')
                                <a data-message="¿Esta seguro de {{$user->active ? 'inactivar' : 'activar'}} este registro?"
                                   href="{{ route('users.destroy',$user->id) }}" class="btn btn-icon {{$user->active ? 'btn-danger' : 'btn-secondary'}}  btn-confirm">
                                    <i class="mdi mdi-{{$user->active ? 'lock' : 'lock-open'}}" title="Activar/Inactivar"></i>
                                </a>
                            @endcan
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            </div>

            <div class="d-flex justify-content-center">
                {!! $users->appends(request()->except(['page','_token']))->render() !!}
            </div>

@endsection
