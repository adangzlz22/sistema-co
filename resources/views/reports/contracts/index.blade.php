@extends('layout.default')


@section('title', 'Reporte de contratos')
@section('description', 'Generar reporte de los contratos')


@section('breadcrumbs')
    {{ Breadcrumbs::render('report.contracts') }}
@endsection

@section('content')

    <div class="card card-body mb-3">
        {!! Form::open(['route' => 'report.contracts_excel', 'method' => 'POST', 'autocomplete' => 'off']) !!}
        <div class="row g-3">

            <div class="col-12 col-sm-4">
                {!! Form::text('contract_number', null, ['class' => 'form-control', 'placeholder' => 'Buscar por número de folio']) !!}
            </div>

            <div class="col-12 col-sm-4">
                {!! Form::text('folio', null, ['class' => 'form-control', 'placeholder' => 'Buscar por folio interno']) !!}
            </div>

            <div class="col-12 col-sm-4">
                {!! Form::text('invoice_number', null, ['class' => 'form-control', 'placeholder' => 'Buscar por número de oficio']) !!}
            </div>

            @if(!Auth::user()->hasRole(Helper::$roleDependenciaEntidad ))
                <div class="col-6 col-sm-4">
                    {!! Form::select('organisms_id', $organisms, null, ['class' => 'form-control select2', 'id' => 'organisms_id','data-placeholder' => 'Seleccionar organismo', 'placeholder' => '']) !!}
                </div>
            @endif

            <div class="col-6 col-sm-4">
                {!! Form::select('contract_type_id', $contract_types, null, ['class' => 'form-control select2', 'id' => 'contract_type_id', 'placeholder' => '','data-placeholder' => 'Seleccionar tipo de contrato']) !!}
            </div>

            <div class="col-12 col-sm-4">
                <div class="input-group input-daterange datepicker p-0" id="datepicker">
                    <span class="input-group-text">Fecha Registro</span>
                    <input type="text" class="form-control " name="received_date_init" placeholder="Desde"
                           value=""/>
                    <span class="input-group-text"><span class="mdi mdi-calendar"></span></span>
                    <input type="text" class="form-control" name="received_date_end" placeholder="Hasta"
                           value=""/>
                </div>
            </div>

            <div class="col-6 col-sm-4">
                {!! Form::select('assignment_method_id', $assignment_methods, null, ['class' => 'form-control select2', 'id' => 'assignment_method_id', 'placeholder' => '','data-placeholder' => 'Seleccionar metodo de asignación']) !!}
            </div>

            <div class="col-6 col-sm-4">
                {!! Form::select('origin_of_resources_id', $origin_of_resources, null, ['class' => 'form-control select2', 'id' => 'origin_of_resources_id', 'placeholder' => '','data-placeholder' => 'Seleccionar origen del recurso']) !!}
            </div>

            <div class="col-12 col-sm-4">
                {!! Form::text('contract_number_general', null, ['class' => 'form-control', 'placeholder' => 'Buscar por número de contrato']) !!}
            </div>

            <div class="col-12 col-sm-4">
                {!! Form::text('legal_name', null, ['class' => 'form-control', 'placeholder' => 'Buscar por persona fisica/moral']) !!}
            </div>

            <div class="col-6 col-sm-4">
                {!! Form::select('priority_id', $priorities, null, ['class' => 'form-control select2', 'id' => 'priority_id', 'placeholder' => '','data-placeholder' => 'Seleccionar prioridad']) !!}
            </div>

            <div class="col-6 col-sm-4">
                {!! Form::select('estatus', $status, null, ['class' => 'form-control select2', 'id' => 'estatus', 'placeholder' => '','data-placeholder' => 'Seleccionar estatus']) !!}
            </div>

            <div class="col-6 col-sm-4">
                {!! Form::select('validated', [true =>  'Si', false => 'No'], null, ['class' => 'form-control select2', 'id' => 'validated', 'placeholder' => '','data-placeholder' => 'Seleccionar validado']) !!}
            </div>


            <div class="col-12 col-sm-12 text-end">
                <a href="{{ route('report.contracts') }}" class="btn btn-default">Restablecer</a>
                {!! Form::submit('Generar excel', ['class' => 'btn btn-info']) !!}
            </div>
        </div>
        {!! Form::close() !!}
    </div>



@endsection

@push('scripts')

@endpush
