<table class="table table-striped table-sm">
    <thead>
        <th>Fecha</th>
        <th>Nombre</th>
        <th>Tipo</th>
        <th>Acción</th>
    </thead>
    <tbody>
        @foreach ($detail as $row)
            <tr>
                <td>{{ date("d/m/Y H:i:s", strtotime($row->created_at))}}</td>
                <td>{{$row->o_name}}</td>
                <td>{{$row->category->name}}</td>
                <td><a class="btn btn-icon btn-info" href="{{$row->url}}" target="_blank" title="Descargar"><i class="mdi mdi-download-outline mdi-16px"></i></a></td>
            </tr>
        @endforeach
    </tbody>
    
</table>