@extends('layout.default')

@section('title', 'Crear icono')
@section('description', '')

@section('breadcrumbs')
    {{ Breadcrumbs::render('icons.create') }}
@endsection

@push('css')

@endpush

@push('scripts')
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    {!! JsValidator::formRequest('App\Http\Requests\IconCreateRequest', '#frmIcons'); !!}
@endpush

@section('content')


    {!! Form::open(['route'=>'icons.store', 'method' => 'POST', 'id' => 'frmIcons']) !!}

    <div class="card">
        <div class="card-body">

            <div class="row g-3">
                <div class="col-sm-12">
                    <div class="form-group">
                        {!! Form::label('name','Nombre',['class'=>'form-label']) !!}
                        {!! Form::text('name', null, ['class'=>'form-control', 'placeholder'=>'Escribe el nombre del icono','required']) !!}
                    </div>
                </div>


                <div class="col-sm-12">
                    <div class="form-group">
                        {!! Form::label('key','Clave',['class'=>'form-label']) !!}
                        {!! Form::text('key', null, ['class'=>'form-control', 'placeholder'=>'Escribe la clave del icono','required']) !!}
                    </div>
                </div>
            </div>
        </div>

        <div class="card-footer">
            <div class="text-right">
                <a href="{{ route('icons.index') }}" type="button" class="btn btn-default">Regresar</a>
                {!! Form::submit('Guardar', ['class'=>'btn btn-info']) !!}
            </div>
        </div>

    </div>


    {!! Form::close() !!}




@endsection



