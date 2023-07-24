@extends('layout.default')



@section('title', 'Menú')
@section('description', 'Ver listado')


@section('breadcrumbs')
    {{ Breadcrumbs::render('menus.index') }}
@endsection
@section('title-action')

    @can('Crear Icono')
        <a class="btn btn-success btn-sm float-right" href="{{ route('menus.create') }}" title="Crear nuevo menú">
            <span class="mdi mdi-plus"></span> NUEVO MENÚ
        </a>
    @endcan

@endsection


@push('css')

@endpush

@push('scripts')
    {{-- <script defer src="{{ asset('js/menus.js') }}"></script> --}}
@endpush

@section('content')
            <div class="card card-body mb-3">
                {!! Form::open(['route'=>'menus.index', 'method' => 'POST', 'autocomplete'=>'off']) !!}
                <div class="row g-3">

                    <div class="col-12 col-sm-4">
                        {!! Form::text('name', $request->name, ['class'=>'form-control', 'placeholder'=>'Buscar por nombre']) !!}
                    </div>
                    <div class="col-12 col-sm-4">
                        {!! Form::select('category_id', $category, $request->category_id, ['class'=>'form-control picker','id'=>'category_id', 'placeholder'=>'Buscar por tipo categoria']) !!}
                    </div>

                    <div class="col-12 col-sm-4">
                        {!! Form::select('permission_id', $permission, $request->permission_id, ['class'=>'form-control picker','id'=>'permission_id', 'placeholder'=>'Buscar por permiso']) !!}
                    </div>
                    <div class="col-12 col-sm-4">
                        {!! Form::select('dropdown', $dropdown, $request->dropdown, ['class'=>'form-control picker','id'=>'dropdown', 'placeholder'=>'Buscar por desplegable']) !!}
                    </div>
                    <div class="col-12 col-sm-4">
                        {!! Form::select('published', $published, $request->published, ['class'=>'form-control picker','id'=>'published', 'placeholder'=>'Buscar por publicado']) !!}
                    </div>

                    <div class="col-12 col-sm-4 text-end">
                        <a href="{{ route('menus.index') }}" class="btn btn-default">Restablecer</a>
                        {!! Form::submit('Filtrar', ['class'=>'btn btn-info']) !!}
                    </div>

                </div>
                {!! Form::close() !!}
            </div>


            <div class="card card-body">
                <table class="table table-striped table-sm">
                <thead>
                <th>Nombre</th>
                <th>Categoría</th>
                <th>Permiso</th>
                <th>Desplegable</th>
                <th>Publicado</th>
                <th>Sub menús</th>
                <th>Orden</th>
                <th>Acciones</th>
                </thead>
                <tbody>
                @foreach ($models as $item)
                    <tr>
                        <td><i class="{{$item->icon->key ?? ''}}"></i> {{ $item->name }}</td>
                        <td>{{ $item->category->name ?? '' }}</td>
                        <td>{{ $item->permission->name ?? '' }}</td>
                        <td>{!! $item->dropdown_label() ?? '' !!}</td>
                        <td>{!! $item->published_label() ?? '' !!}</td>
                        <td>{{ $item->sons() }}</td>
                        <td>{{ $item->order }}</td>
                        <td>
                            @can('Editar Icono')
                                <a href="{{ route('menus.edit', $item->id) }}" class="btn btn-icon btn-warning">
                                    <i class="mdi mdi-file-document-edit" title="Editar"></i>
                                </a>
                            @endcan

                            @can('Eliminar Icono')
                                <a data-message="¿Está seguro de eliminar este registro?"
                                   href="{{ route('menus.destroy',$item->id) }}" class="btn btn-icon btn-danger btn-confirm">
                                    <i class="mdi mdi-trash-can" title="Eliminar"></i>
                                </a>
                            @endcan
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            </div>

            <div class="d-flex justify-content-center">
                {!! $models->appends(request()->except(['page','_token']))->render() !!}
            </div>

@endsection

@push("scripts")

    <script>
        $(function(){

            $('#category_id').select2({
                theme: 'bootstrap-5',
                language: 'es',
                placeholder: 'Seleccionar categoría',
                allowClear: true
            });
            $('#permission_id').select2({
                theme: 'bootstrap-5',
                language: 'es',
                placeholder: 'Seleccionar permiso',
                allowClear: true
            });
            $('#dropdown').select2({
                theme: 'bootstrap-5',
                language: 'es',
                placeholder: 'Seleccionar por desplegable',
                allowClear: true
            });
            $('#published').select2({
                theme: 'bootstrap-5',
                language: 'es',
                placeholder: 'Seleccionar por publicado',
                allowClear: true
            });
        })
    </script>


@endpush
