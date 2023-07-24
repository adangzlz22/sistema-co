
<div class="card card-body" id="IndexList">
    <table class="table table-striped table-sm">
        <thead>
        <th>Usuario</th>
        <th>Fecha</th>
        <th>Movimiento</th>
        <th>Catálogo</th>
        </thead>
        <tbody>
        @foreach ($model as $item)
            <tr>
                <td>{{ $item->name }}</td>
                <td>{{ date('d/m/Y H:m:s', strtotime($item->date)) }}</td>
                <td>{{ \App\Enums\log_movements::getDescription($item->movement) }}</td>
                <td>{{ \App\Enums\system_catalogues::getDescription($item->catalogue) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <div class="d-flex justify-content-center">
        {!! $model->appends(request()->except(['page','_token']))->render() !!}
    </div>
</div>


{{--@include("layout.partial._pagination", ['model' => $model])--}}
