@extends('layout.default')
@section('title', 'Editar lugar de reunión')
@section('description', 'Actualiza los datos de un lugar de reunión en específico')

@section('breadcrumbs')
    {{ Breadcrumbs::render('places.edit', $model) }}
@endsection

@push('css')

@endpush

@push('scripts')
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    {!! JsValidator::formRequest('App\Http\Requests\PlaceCreateRequest', '#frmPlace'); !!}
@endpush

@section('content')

    {!! Form::open(['route'=>['places.update', $model], 'method' => 'PUT', 'autocomplete'=>'off', 'id' => 'frmPlace']) !!}

    <div class="card">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-sm-2">
                    <div class="form-group">
                        {!! Form::label('name','Nombre:',['class'=>'form-label']) !!}
                        {!! Form::text('name', $model->name, ['class'=>'form-control', 'placeholder'=>'Escribe el nombre del lugar','required']) !!}
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="form-group">
                        {!! Form::label('address','Dirección:',['class'=>'form-label']) !!}
                        {!! Form::text('address', $model->address, ['class'=>'form-control', 'placeholder'=>'Escribe la dirección del lugar','required']) !!}
                    </div>
                </div>
                
                {{--<div class="col-sm-4">
                    <div class="form-group">
                        {!! Form::label('meeting_type_id','Tipo de Reunion:',['class'=>'form-label']) !!}
                        {!! Form::select('meeting_type_id', $arrMeetingsTypes, $model->meeting_type_id, ['class'=>'form-control picker select2', 'id'=>'meeting_type_id', 'placeholder'=> 'Seleccione Tipo de Reunión']) !!}
                    </div>
                </div>--}}
            </div>
        </div>

        <div class="card-footer">
            <div class="text-right">
                <a href="{{ route('places.index') }}" type="button" class="btn btn-default">Regresar</a>
                {!! Form::submit('Editar', ['class'=>'btn btn-info']) !!}
            </div>
        </div>
    </div>

    {!! Form::close() !!}

@endsection


