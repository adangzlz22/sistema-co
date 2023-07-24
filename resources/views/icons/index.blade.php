@extends('layout.default')



@section('title', 'Iconos')
@section('description', 'Ver listado')


@section('breadcrumbs')
    {{ Breadcrumbs::render('icons.index') }}
@endsection
@section('title-action')

    @can('Crear Icono')
        <a class="btn btn-success btn-sm float-right" href="{{ route('icons.create') }}" title="Crear nuevo icono">
            <span class="mdi mdi-plus"></span> NUEVO ICONO
        </a>
    @endcan

@endsection


@push('css')

@endpush

@push('scripts')
    <script defer src="{{ asset('js/icons.js') }}"></script>
@endpush

@section('content')


            <div class="card card-body mb-3">
                {!! Form::open(['route'=>'icons.index', 'method' => 'POST', 'autocomplete'=>'off']) !!}
                <div class="row g-3">

                    <div class="col-12 col-sm-4">
                        {!! Form::text('name', $request->name, ['class'=>'form-control', 'placeholder'=>'Buscar por nombre']) !!}
                    </div>
                    <div class="col-12 col-sm-4">
                        {!! Form::text('key', $request->key, ['class'=>'form-control', 'placeholder'=>'Buscar por clave']) !!}
                    </div>

                    <div class="col-12 col-sm-4 text-end">
                        <a href="{{ route('icons.index') }}" class="btn btn-default">Restablecer</a>
                        {!! Form::submit('Filtrar', ['class'=>'btn btn-info']) !!}
                    </div>

                </div>
                {!! Form::close() !!}
            </div>


            <div class="card card-body">
                <table class="table table-striped table-sm">
                <thead>
                <th>Nombre</th>
                <th>Clave</th>
                <th>Acciones</th>
                </thead>
                <tbody>
                @foreach ($models as $item)
                    <tr>
                        <td><i class="{{$item->key}}"></i> {{ $item->name }}</td>
                        <td>{{ $item->key }}</td>
                        <td>
                            @can('Editar Icono')
                                <a href="{{ route('icons.edit', $item->id) }}" class="btn btn-icon btn-warning">
                                    <i class="mdi mdi-file-document-edit" title="Editar"></i>
                                </a>
                            @endcan

                            @can('Eliminar Icono')
                                <a data-message="¿Está seguro de eliminar este registro?"
                                   href="{{ route('icons.destroy',$item->id) }}" class="btn btn-icon btn-danger btn-confirm">
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
