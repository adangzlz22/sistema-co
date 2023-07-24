<table class="table table-striped table-sm">
    <thead>
        <th>Institucion</th>
        <th>#Acciones</th>
        <th>#Sin avance</th>
        <th>#En proceso</th>
        <th>#Concluidas</th>
        <th>% Avance</th>
    </thead>
    <tbody>
        @foreach ($detail as $row)
            <tr>
                <td>{{$row->entity}}</td>
                <td>{{$row->total}}</td>
                <td>{{$row->sin_avance}}</td>
                <td>{{$row->en_proceso}}</td>
                <td>{{$row->concluidas}}</td>
                <td>{{$row->percent}}</td>
            </tr>
        @endforeach
    </tbody>
    
</table>