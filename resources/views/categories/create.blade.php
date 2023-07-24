@extends('layout.default')

@section('title', 'Crear categoría del menú')
@section('description', '')

@section('breadcrumbs')
    {{ Breadcrumbs::render('categories.create') }}
@endsection

@push('css')

@endpush

@push('scripts')
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    {!! JsValidator::formRequest('App\Http\Requests\CategoryCreateRequest', '#frmCategory'); !!}
@endpush

@section('content')


    {!! Form::open(['route'=>'categories.store', 'method' => 'POST', 'id' => 'frmCategory' ]) !!}



    <div class="card">
        <div class="card-body">

            <div class="row g-3">
                <div class="col-12">
                    <div class="form-group">
                        {!! Form::label('name','Nombre',['class'=>'form-label']) !!}
                        {!! Form::text('name', null, ['class'=>'form-control', 'placeholder'=>'Escribe el nombre de la categoría del menú','required']) !!}
                    </div>
                </div>


                <div class="col-12">
                    <div class="form-group">
                        {!! Form::label('description','Descripción',['class'=>'form-label']) !!}
                        {!! Form::text('description', null, ['class'=>'form-control', 'placeholder'=>'Escribe una descripción de la categoría del menú','required']) !!}
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        {!! Form::label('order','Orden',['class'=>'form-label']) !!}
                        {!! Form::number('order', null, ['class'=>'form-control', 'placeholder'=>'Escribe el orden que tendrá en el menú']) !!}
                    </div>
                </div>
            </div>
        </div>

        <div class="card-footer">
            <div class="text-right">
                <a href="{{ route('categories.index') }}" type="button" class="btn btn-default">Regresar</a>
                {!! Form::submit('Guardar', ['class'=>'btn btn-info']) !!}
            </div>
        </div>

    </div>


    {!! Form::close() !!}




@endsection



