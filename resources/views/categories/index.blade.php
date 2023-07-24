@extends('layout.default')



@section('title', 'Categorías del menú')
@section('description', 'Ver listado')


@section('breadcrumbs')
    {{ Breadcrumbs::render('categories.index') }}
@endsection
@section('title-action')

    @can('Crear Categoria')
        <a class="btn btn-success btn-sm float-right" href="{{ route('categories.create') }}" title="Crear nueva categoría del menú">
            <span class="mdi mdi-plus"></span> NUEVA CATEGORÍA
        </a>
    @endcan

@endsection


@section('content')


            <div class="card card-body mb-3">
                {!! Form::open(['route'=>'categories.index', 'method' => 'POST', 'autocomplete'=>'off']) !!}
                <div class="row g-3">

                    <div class="col-12 col-sm-4">
                        {!! Form::text('name', $request->name, ['class'=>'form-control', 'placeholder'=>'Buscar por nombre']) !!}
                    </div>
                    <div class="col-12 col-sm-4">
                        {!! Form::text('description', $request->description, ['class'=>'form-control', 'placeholder'=>'Buscar por descripción']) !!}
                    </div>

                    <div class="col-12 col-sm-4 text-end">
                        <a href="{{ route('categories.index') }}" class="btn btn-default">Restablecer</a>
                        {!! Form::submit('Filtrar', ['class'=>'btn btn-info']) !!}
                    </div>

                </div>
                {!! Form::close() !!}
            </div>


            <div class="card card-body">
                <form id="sortable">
                    <table class="table table-striped table-sm">
                <thead>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Orden</th>
                <th>Acciones</th>
                </thead>
                <tbody>
                @foreach ($models as $item)
                    <tr>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->description }}</td>
                        <td class="w-50px"><input type="text" readonly value="{{$item->order}}" class="form-control form-control-sm order text-center" name="order[{{ $item->id }}]"> </td>
                        <td>
                            @can('Editar Categoria')
                                <a href="{{ route('categories.edit', $item->id) }}" class="btn btn-icon btn-warning">
                                    <i class="mdi mdi-file-document-edit" title="Editar"></i>
                                </a>
                            @endcan

                            @can('Eliminar Categoria')
                                <a data-message="¿Está seguro de eliminar este registro?"
                                   href="{{ route('categories.destroy',$item->id) }}" class="btn btn-icon btn-danger btn-confirm">
                                    <i class="mdi mdi-trash-can" title="Eliminar"></i>
                                </a>
                            @endcan
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
                </form>
            </div>


            <div class="d-flex justify-content-center">
                {!! $models->appends(request()->except(['page','_token']))->render() !!}
            </div>

@endsection


@push('scripts')

    <script defer src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>

    <script type="text/javascript">


        (function($) {
            $(document).ready(function () {
                $( "table tbody" ).sortable( {

                    //items: ".order",
                    update: function( event, ui ) {


                        $(this).children().each(function(index) {
                            $(this).find('input.order').val(index + 1)
                        });






                        var data = $('form#sortable').serialize();
                        //var data = $('table tbody .order').sortable('toArray').toString();

                        // POST to server using $.post or $.ajax
                        $.ajax({
                            data: data,
                            type: 'PUT',
                            url: '{{ route('categories.sortable') }}'
                        });


                    }
                });
            });
        })(jQuery);

    </script>
@endpush
