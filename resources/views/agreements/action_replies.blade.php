<div class="row p-2">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <strong>{{$action->action}}</strong>
                </div>
                <div class="card-body">
                    <table class="table table-striped table-sm">
                        <thead>
                            <th>Fecha</th>
                            <th>Institución</th>
                            <th>Avance</th>
                        </thead>
                        <tbody>
                            @foreach ($action->replies as $reply)
                                <tr>
                                    <td>{{ date("d/m/Y H:i:s", strtotime($reply->created_at))}} Hrs</td>
                                    <td>{{$reply->entity->acronym??"Administrador"}} <br><small>{{$reply->user->name}}</small></td>
                                    <td>{{$reply->reply}}</td>
                                </tr>
                            @endforeach
                            <tr>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>