@extends('layout.default')

@section('title','Tablero')

@section('breadcrumbs')
    {{ Breadcrumbs::render('home') }}
@endsection


@section('content')
<div class="row">
    <div class="col-12 text-right"><button class="btn btn-info" onclick="download()">Descargar</button></div>
</div>
<div id="capture" style="padding: 10px;">
    <div class="card mb-3">
        {!! Form::open(['route'=>'dashboard.gabinete.index', 'method' => 'POST', 'autocomplete'=>'off']) !!}
        <div class="card-body">
        <div class="row g-3">
            <div class="col-sm-12 col-md-6">
                <div class="input-group input-daterange datepicker p-0" id="datepicker">
                    <span class="input-group-text">Fecha</span>
                    <input type="text" class="form-control " name="start_date" id="start_date" placeholder="Desde" value="{{ $request->start_date ?? '' }}"  />
                    <span class="input-group-text"><span>a</span></span>
                    <input type="text" class="form-control" name="end_date" id="end_date" placeholder="Hasta" value="{{ $request->end_date ?? '' }}"  />
                </div>
            </div>
            <div class="col-md-6 col-sm-12">
                {!! Form::select('entity_id', $entities, $request->entity_id, ['class'=>'form-control select2','placeholder' => '','id'=>'entity_id','data-placeholder' => 'Seleccionar Institución']) !!}
            </div>

        </div>
        </div>
        <div class="card-footer">
            <div class="text-right">
                <a href="{{ route('dashboard.gabinete.index') }}" class="btn btn-default">Restablecer</a>
                {!! Form::submit('Filtrar', ['class'=>'btn btn-info']) !!}
            </div>
        </div>
        {!! Form::close() !!}
    </div>

    <div class="row mt-3 g-1">

        <div class="col-12">
            <div class="card card-body">

                <h5 class="mb-1 text-center">INFORME DE CUMPLIMIENTO DE GABINETES</h5>
                @if(empty($data["table"]))
                    <div class="alert alert-info">
                        <i class="mdi mdi-information-outline mdi-24px"></i>
                        No se cuenta con información para mostrar.
                    </div>
                @else
                    <div id="table_meetings" class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <thead class="bg-danger text-white">
                                <th>Tipo de reunión</th>
                                <th>Reuniones celebradas</th>
                                <th>Acuerdos totales</th>
                                <th>Cumplimiento general</th>
                                <th>Consultar</th>
                            </thead>
                            <tbody>
                                @foreach ($data["table"] as $r)
                                <tr onclick="linkConsulta('{{$r->type_id}}')">
                                    <td>{{$r->type}}</td>
                                    <td>{{$r->t_m}}</td>
                                    <td>{{$r->t_a}}</td>
                                    <td>{{$r->t_percent>0?round(($r->t_percent/$r->t_a),2):0.00}}%</td>
                                    <td>
                                        <button class="btn btn-icon btn-danger" data-toggle="tooltip" data-placement="top" title="Consultar" onclick="linkConsulta('{{$r->type_id}}')">
                                            <i class="mdi mdi-text-box-search"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

        </div>

        <div class="col-12">
            <div class="card card-body">

                <h5 class="mb-3 text-center">CUMPLIMIENTO DE ACUERDOS</h5>
                <div class="row g-1">
                    <div class="col-md-6">
                        <div class="card card-body">
            
                            <h5 class="mb-1 text-center">Dependencias</h5>
                            @if($data["dep"]=="null")
                                <div class="alert alert-info">
                                    <i class="mdi mdi-information-outline mdi-24px"></i>
                                    No se cuenta con información para mostrar.
                                </div>
                            @else
                                <div class="chartdiv" id="graph-agreement-dep"></div>
                            @endif
                        </div>
            
                    </div>
            
                    <div class="col-md-6">
                        <div class="card card-body">
            
                            <h5 class="mb-1 text-center">Entidades</h5>
                                @if($data["ent"]=="null")
                                    <div class="alert alert-info">
                                        <i class="mdi mdi-information-outline mdi-24px"></i>
                                        No se cuenta con información para mostrar.
                                    </div>
                                @else
                                    <div class="chartdiv" id="graph-agreement-ent"></div>
                                @endif
                        </div>
            
                    </div>
            
                    <div class="col-md-6">
                        <div class="card card-body">
            
                            <h5 class="mb-1 text-center">Organismos</h5>
                                @if($data["org"]=="null")
                                    <div class="alert alert-info">
                                        <i class="mdi mdi-information-outline mdi-24px"></i>
                                        No se cuenta con información para mostrar.
                                    </div>
                                @else
                                    <div class="chartdiv" id="graph-agreement-org"></div>
                                @endif
                        </div>
            
                    </div>
            
                    <div class="col-md-6">
                        <div class="card card-body">
            
                            <h5 class="mb-1 text-center">Fideicomisos</h5>
                                @if($data["fid"]=="null")
                                    <div class="alert alert-info">
                                        <i class="mdi mdi-information-outline mdi-24px"></i>
                                        No se cuenta con información para mostrar.
                                    </div>
                                @else
                                    <div class="chartdiv" id="graph-agreement-fid"></div>
                                @endif
                        </div>
            
                    </div>
                </div>
            </div>

        </div>
        
    </div>
</div>
        @endsection

        @push("css")
        <style>
            .chartdiv {
              width: 100%;
              height: 300px;
            }
        </style>
        @endpush

        @push("scripts")
        <script src="https://cdn.amcharts.com/lib/4/core.js"></script>
        <script src="https://cdn.amcharts.com/lib/4/charts.js"></script>
        <script src="https://cdn.amcharts.com/lib/4/themes/animated.js"></script>
        <script src="{{ asset('js/plugins/html2canvas/html2canvas.min.js') }}"></script>
            <script>
                var data_dep = {!! $data["dep"] !!};
                var data_ent = {!! $data["ent"] !!};
                var data_org = {!! $data["org"] !!};
                var data_fid = {!! $data["fid"] !!};
                var div_dep = "graph-agreement-dep";
                var div_ent = "graph-agreement-ent";
                var div_org = "graph-agreement-org";
                var div_fid = "graph-agreement-fid";

                am4core.ready(function() {
                    am4core.useTheme(am4themes_animated);
                    creaGrafico(div_dep,data_dep);
                    creaGrafico(div_ent,data_ent);
                    creaGrafico(div_org,data_org);
                    creaGrafico(div_fid,data_fid);

                });
            
            function creaGrafico(div,data) {
                let agreement_graph = am4core.create(div, am4charts.PieChart3D);
                    agreement_graph.hiddenState.properties.opacity = 0;

                    agreement_graph.legend = new am4charts.Legend();

                    agreement_graph.data = [
                        {estatus:"SIN AVANCE",total:data.sa, color: am4core.color("#666666") },
                        {estatus:"EN PROCESO",total:data.ep, color: am4core.color("#DC7F37")},
                        {estatus:"CONCLUIDO",total:data.c, color: am4core.color("#2F9B8C")},
                    ];

                    let series = agreement_graph.series.push(new am4charts.PieSeries3D());
                    series.slices.template.propertyFields.fill = "color";
                    series.dataFields.value = "total";
                    series.dataFields.category = "estatus";
            }
                
            function linkConsulta(tipo) {
                let datos = btoa('tipo='+tipo+'&startdate='+($("#start_date").val()??"")+'&enddate='+($("#end_date").val()??""));	
                console.log(datos);
                let url="{{route('dashboard.gabinete.link',':params')}}";
                url = url.replace(':params', datos);
                location.assign(url);
            }
            function download() {
                html2canvas(document.querySelector("#capture")).then(function(canvas) {
                    saveAs(canvas.toDataURL(), 'Tablero.png');
                });
            }
            function saveAs(uri, filename) {
                var link = document.createElement('a');
                if (typeof link.download === 'string') {
                    link.href = uri;
                    link.download = filename;
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                } else {
                    window.open(uri);
                }
            }
            </script>

    @endpush
