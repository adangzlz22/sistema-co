@extends('layout.default')

@section('title', 'Crear rol')
@section('description', 'Crea roles de acceso para los usuarios del sistema')

@section('breadcrumbs')
    {{ Breadcrumbs::render('roles.create') }}
@endsection

@push('scripts')
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    {!! JsValidator::formRequest('App\Http\Requests\RolesStoreRequest', '#frmRol'); !!}
@endpush

@section('content')


    {!! Form::open(['route'=>'roles.store', 'method' => 'POST', 'id' => 'frmRol']) !!}

    <div class="card">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-sm-6">
                    <div class="form-group">
                        {!! Form::label('name','Nombre',['class'=>'form-label']) !!}
                        {!! Form::text('name', null, ['class'=>'form-control', 'placeholder'=>'Escribe un nombre para el rol de usuario','required']) !!}
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <div class="text-right">
                <a href="{{ route('roles.index') }}" type="button" class="btn btn-default">Regresar</a>
                {!! Form::submit('Registrar', ['class'=>'btn btn-info']) !!}
            </div>
        </div>
    </div>

    {!! Form::close() !!}


@endsection
