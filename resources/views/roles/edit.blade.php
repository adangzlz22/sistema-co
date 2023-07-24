@extends('layout.default')

@section('title', 'Editar rol de acceso')
@section('description', '')

@section('breadcrumbs')
    {{ Breadcrumbs::render('roles.edit',$role) }}
@endsection

<script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
{!! JsValidator::formRequest('App\Http\Requests\RolesStoreRequest', '#frmRol'); !!}

@section('content')


    {!! Form::open(['route'=>['roles.update',$role], 'method' => 'PUT', 'id' => 'frmRol']) !!}

    <div class="card">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-sm-6">
                    <div class="form-group">
                        {!! Form::label('name','Nombre',['class'=>'form-label']) !!}
                        {!! Form::text('name', $role->name, ['class'=>'form-control', 'placeholder'=>'Escribe un nombre para el rol de usuario','required']) !!}
                    </div>
                </div>

            </div>
        </div>

        <div class="card-footer">
            <div class="text-right">
                <a href="{{ route('roles.index') }}" type="button" class="btn btn-default">Regresar</a>
                {!! Form::submit('Editar', ['class'=>'btn btn-info']) !!}
            </div>
        </div>

    </div>

    {!! Form::close() !!}


@endsection
