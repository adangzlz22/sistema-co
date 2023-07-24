@extends('layout.default')



@section('title', 'Reportes')
@section('description', 'Ver listado de los reportes')


@section('breadcrumbs')
    {{ Breadcrumbs::render('custom_reports.index') }}
@endsection
@section('title-action')

@endsection


@push('css')

@endpush

@push('scripts')

@endpush

@section('content')


            <div class="card card-body mb-3">
                {!! Form::open(['route'=>'custom_reports.index', 'method' => 'POST', 'autocomplete'=>'off']) !!}
                <div class="row g-3">


                    <div class="col-12 col-sm-4 text-end">
                        <a href="{{ route('custom_reports.index') }}" class="btn btn-default">Restablecer</a>
                        {{--{!! Form::submit('Filtrar', ['class'=>'btn btn-info']) !!}--}}
                    </div>

                </div>
                {!! Form::close() !!}
            </div>


            <div class="card card-body">
                <table class="table table-striped table-sm">
                <thead>
                <th>Reporte</th>
                <th>Parametrros</th>
                <th>Acciones</th>
                </thead>
                <tbody>
                @foreach ($models as $item)
                    <tr>
                        <td>{{ \App\Enums\custom_reports::getDescription($item->report) }}</td>
                        <td>{{ \Illuminate\Support\Str::replace('|', ', ', $item->parameters) }}</td>
                        <td>
                            @can('Editar Reporte')
                                <a href="{{ route('custom_reports.edit', $item->id) }}" class="btn btn-icon btn-warning">
                                    <i class="mdi mdi-file-document-edit"></i>
                                </a>
                            @endcan
                                <a href="{{ route('custom_reports.generate_pdf', $item->id) }}" class="btn btn-icon btn-info">
                                    <i class="mdi mdi-file-pdf-box"></i>
                                </a>
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
