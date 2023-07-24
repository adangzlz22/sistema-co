@extends('layout.default')

@section('title','Inicio')

@section('breadcrumbs')
    {{ Breadcrumbs::render('home') }}
@endsection


@section('content')
    <hr>
    <h3>
        <!-- Contratos -->
    </h3>
    @php
        $qr_code = QrCode::format('svg')->generate('SSP/UAJ/2021-2/1809');
    @endphp

    <div class="card card-body mb-3">
        {!! Form::open(['route'=>'home', 'method' => 'POST', 'autocomplete'=>'off']) !!}
        <div class="row g-3">

            @if(!Auth::user()->hasRole(Helper::$roleDependenciaEntidad ))
                {{--<div class="col-6 col-sm-3">
                    {!! Form::select('entity_id', $entities, $request->entity_id, ['class'=>'form-control select2','placeholder' => 'Seleccionar Institución','id'=>'entity_id']) !!}
                </div>--}}
            @endif

            <div class="col-12 col-sm-9">
                <div class="input-group input-daterange datepicker p-0" id="datepicker">
                    <span class="input-group-text">Fecha</span>
                    <input type="text" class="form-control " name="init_date" placeholder="Desde" value="{{ $request->init_date ?? '' }}"  />
                    <span class="input-group-text"><span>a</span></span>
                    <input type="text" class="form-control" name="end_date" placeholder="Hasta" value="{{ $request->end_date ?? '' }}"  />
                </div>
            </div>

            <div class="col-12 col-sm-3 text-end">
                <a href="{{ route('home') }}" class="btn btn-default">Restablecer</a>
                {!! Form::submit('Filtrar', ['class'=>'btn btn-info']) !!}
            </div>

        </div>
        {!! Form::close() !!}
    </div>

    @php



            @endphp
    <div class="row mt-3 g-1">

        <div class="col-sm-6">
            <div class="card bg-dash-one text-white">
                <div class="card-body text-center">
                    <h1 class="mb-0 display-3">{{ number_format(0,0,'.',',') }}</h1>
                    <strong class="">Total de instituciones</strong>
                </div>

            </div>
        </div>

        <div class="col-sm-6">
            <div class="card bg-dash-two text-white">
                <div class="card-body text-center">
                    <h1 class="mb-0 display-3">{{ number_format(0,0,'.',',') }}</h1>
                    <strong class="">Total de acuerdos</strong>
                </div>

            </div>
        </div>


        <div class="col-sm-6">
            <div class="card card-body">

                <h5 class="mb-1 text-center">Total de acuerdos por institución</h5>
                @if(0 == 0)
                    <div class="alert alert-info">
                        <i class="mdi mdi-information-outline mdi-24px"></i>
                        Aún no se cuenta con información para mostrar.
                    </div>
                @else
                    <div id="contratosOrganismo"></div>

                @endif


            </div>

        </div>



        <div class="col-sm-6">
            <div class="card card-body">

                <h5 class="mb-1 text-center">Total de acuerdos por estatus</h5>
                @if(0 == 0)
                    <div class="alert alert-info">
                        <i class="mdi mdi-information-outline mdi-24px"></i>
                        Aún no se cuenta con información para mostrar.
                    </div>
                @else
                    <div id="contratosEstatus"></div>

                @endif


            </div>

        </div>


        <div class="col-sm-6">
            <div class="card card-body">

                <h5 class="mb-1 text-center">Total de acuerdos por tipo de reunión</h5>
                @if(0 == 0)
                    <div class="alert alert-info">
                        <i class="mdi mdi-information-outline mdi-24px"></i>
                        Aún no se cuenta con información para mostrar.
                    </div>
                @else
                    <div id="contratosPrioridad"></div>

                @endif


            </div>

        </div>


        <div class="col-sm-6">
            <div class="card card-body">

                {{--<h5 class="mb-1 text-center">Total de </h5>
                @if(0 == 0)
                    <div class="alert alert-info">
                        <i class="mdi mdi-information-outline mdi-24px"></i>
                        Aún no se cuenta con información para mostrar.
                    </div>
                @else
                    <div id="contratosTipo"></div>

                @endif --}}


            </div>

        </div>

        @endsection

        @push("css")

        @endpush

        @push("scripts")
            {{-- <script src="{{ asset('js/plugins/apexcharts/dist/apexcharts.min.js') }}" type="text/javascript"></script> --}}
            {{-- <script src="{{ asset('js/demo/dashboard.demo.js') }}" type="text/javascript"></script>--}}


            <style>
              #contratosOrganismo, #contratosEstatus, #contratosPrioridad,#contratosTipo {
                width: 100%;
                height: 500px;
              }
            </style>








            <script src="https://cdn.amcharts.com/lib/4/core.js"></script>
            <script src="https://cdn.amcharts.com/lib/4/charts.js"></script>
            <script src="https://cdn.amcharts.com/lib/4/themes/animated.js"></script>

            <script src="https://www.amcharts.com/lib/4/themes/material.js"></script>



            <script>
                // Create chart instance
                am4core.useTheme(am4themes_material);
                var chart = am4core.create("contratosOrganismo", am4charts.PieChart);
                chart.radius = am4core.percent(50);
                // Add data
                chart.data = {!! "{}" !!};




                // Add and configure Series
                var pieSeries = chart.series.push(new am4charts.PieSeries());
                pieSeries.dataFields.value = "value";
                pieSeries.dataFields.category = "name";
                //pieSeries.slices.template.propertyFields.fill = "color";


                //pieSeries.tooltip.getFillFromObject = false;
                //pieSeries.tooltip.label.propertyFields.fill = "color";
                pieSeries.labels.template.fontSize = 10;
                pieSeries.labels.template.text = "{short_name}";
                //pieSeries.tooltip.background.propertyFields.stroke = "color";


            </script>

            <script>
                // Create chart instance
                am4core.useTheme(am4themes_material);
                var chart = am4core.create("contratosTipo", am4charts.PieChart);
                chart.radius = am4core.percent(50);
                // Add data
                chart.data = {!! "{}" !!};




                // Add and configure Series
                var pieSeries = chart.series.push(new am4charts.PieSeries());
                pieSeries.dataFields.value = "value";
                pieSeries.dataFields.category = "name";
                //pieSeries.slices.template.propertyFields.fill = "color";


                //pieSeries.tooltip.getFillFromObject = false;
                //pieSeries.tooltip.label.propertyFields.fill = "color";
                pieSeries.labels.template.fontSize = 10;
                pieSeries.labels.template.text = "{name}";
                //pieSeries.tooltip.background.propertyFields.stroke = "color";


            </script>



            <script>
                // Create chart instance
                am4core.useTheme(am4themes_material);
                var chart = am4core.create("contratosPrioridad", am4charts.PieChart);
                chart.radius = am4core.percent(50);
                // Add data
                chart.data = {!! "{}" !!};

                // Add and configure Series
                var pieSeries = chart.series.push(new am4charts.PieSeries());
                pieSeries.dataFields.value = "value";
                pieSeries.dataFields.category = "name";
                pieSeries.slices.template.propertyFields.fill = "color";


                pieSeries.tooltip.getFillFromObject = false;
                pieSeries.tooltip.label.propertyFields.fill = "color";
                pieSeries.labels.template.fontSize = 10;
                pieSeries.labels.template.text = "{name}";
                pieSeries.tooltip.background.propertyFields.stroke = "color";


            </script>



            <script>
                // Create chart instance
                am4core.useTheme(am4themes_animated);
                var chart = am4core.create("contratosEstatus", am4charts.PieChart);
                chart.radius = am4core.percent(50);
                // Add data
                chart.data = {!! "{}" !!};




                // Add and configure Series
                var pieSeries = chart.series.push(new am4charts.PieSeries());
                pieSeries.dataFields.value = "value";
                pieSeries.dataFields.category = "name";
                pieSeries.slices.template.propertyFields.fill = "color";

                pieSeries.labels.template.fontSize = 10;
                pieSeries.labels.template.text = "{name}";


                pieSeries.tooltip.getFillFromObject = false;
                pieSeries.tooltip.label.propertyFields.fill = "color";
                pieSeries.tooltip.background.propertyFields.stroke = "color";

            </script>






    @endpush
