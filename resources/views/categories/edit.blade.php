@extends('layout.default')
@section('title', 'Editar categoría del menú')
@section('description', 'Actualiza los datos de una categoría del menú en específico')

@section('breadcrumbs')
    {{ Breadcrumbs::render('categories.edit',$model) }}
@endsection

@push('css')

@endpush

@push('scripts')


    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    {!! JsValidator::formRequest('App\Http\Requests\CategoryCreateRequest', '#frmCategory'); !!}
@endpush

@section('content')




    {!! Form::open(['route'=>['categories.update',$model], 'method' => 'PUT', 'autocomplete'=>'off', 'id' => 'frmCategory']) !!}



    <div class="card">
        <div class="card-body">

            <div class="row g-3">
                <div class="col-12">
                    <div class="form-group">
                        {!! Form::label('name','Nombre',['class'=>'form-label']) !!}
                        {!! Form::text('name', $model->name, ['class'=>'form-control', 'placeholder'=>'Escribe el nombre de la categoría del menú','required']) !!}
                    </div>
                </div>


                <div class="col-12">
                    <div class="form-group">
                        {!! Form::label('description','Descripción',['class'=>'form-label']) !!}
                        {!! Form::text('description', $model->description, ['class'=>'form-control', 'placeholder'=>'Escribe una descripción de la categoría del menú','required']) !!}
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        {!! Form::label('order','Orden',['class'=>'form-label']) !!}
                        {!! Form::number('order', $model->order, ['class'=>'form-control', 'placeholder'=>'Escribe el orden que tendrá en el menú']) !!}
                    </div>
                </div>
            </div>
        </div>

        <div class="card-footer">
            <div class="text-right">
                <a href="{{ route('categories.index') }}" type="button" class="btn btn-default">Regresar</a>
                {!! Form::submit('Editar', ['class'=>'btn btn-info']) !!}
            </div>
        </div>
    </div>


    {!! Form::close() !!}




@endsection


