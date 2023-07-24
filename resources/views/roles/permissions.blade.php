@extends('layout.default')

@section('title', $role->name)
@section('description', 'Asigna permisos al rol de acceso')



@section('breadcrumbs')
    {{ Breadcrumbs::render('roles.assing',$role) }}
@endsection



@section('content')

    {!! Form::open(['route'=>['roles.permissions',$role], 'method' => 'POST']) !!}

    <h5></h5>

    <div class="card">
        <div class="card-body">
            <p><a href="#" class="seleccionar">Todos</a> / <a href="#" class="deseleccionar">Ninguno</a></p>
            <div id="permissions" class="row g-3">
                @foreach($groupPermissions as $group)
                    <div class="col-sm-4">
                        <strong>{{ $group->name }}</strong>
                        @foreach($group->permissions as $permission)

                            @php $check = false @endphp

                            @foreach($role->permissions as $rPermission)
                                @if($rPermission->id == $permission->id )
                                    @php $check = true @endphp
                                @endif
                            @endforeach

                            <div class="">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault">
                                    {!! Form::checkbox('permission[]', $permission->id,  $check ,['class'=>'form-check-input','id'=>'chbx-'.$permission->id]) !!}
                                    <label class="form-check-label" for="chbx-{{ $permission->id  }}"> {{ $permission->name }}</label>
                                </div>
                            </div>

                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>

        <div class="card-footer">
            <div class="text-right">
                <a href="{{ route('roles.index') }}" type="button" class="btn btn-default">Regresar</a>
                {!! Form::submit('Asignar', ['class'=>'btn btn-primary']) !!}
            </div>
        </div>
    </div>

    {!! Form::close() !!}

@endsection

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function () {
            $('.seleccionar').click(function (e) {
                e.preventDefault();
                $("#permissions").find('input:checkbox').prop("checked", true);
            });

            $('.deseleccionar').click(function (e) {
                e.preventDefault();
                $("#permissions").find('input:checkbox').prop("checked", false);
            });
        });
    </script>
@endpush
