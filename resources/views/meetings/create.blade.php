@extends('layout.default')

@section('title', 'Crear reunión')
@section('description', '')

@section('breadcrumbs')
    {{Breadcrumbs::render('meetings.create') }}
@endsection

@push('css')

@endpush

@push('scripts')
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    {!! JsValidator::formRequest('App\Http\Requests\MeetingCreateRequest', '#frmMeeting'); !!}
@endpush

@section('content')

    {!! Form::open(['route'=>'meetings.store', 'method' => 'POST', 'id' => 'frmMeeting', 'files' => true]) !!}
    <div class="card">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-sm-3">
                    <div class="form-group">
                        {!! Form::label('meeting_type', 'Tipo de reunión:',['class'=>'form-label']) !!}
                        {!! Form::select('meeting_type', $arrMeetingTypes, old('meeting_type') ?? [], ['class'=>'form-control picker select2', 'id'=>'meeting_type', 'placeholder'=> 'Seleccione tipo de reunión']) !!}
                    </div>
                </div>

                <div class="col-sm-3">
                    <div class="form-group">
                        {!! Form::label('date','Fecha de la reunión:',['class'=>'form-label']) !!}
                        {!! Form::date('date', null, ['class'=>'form-control', 'id' => 'date', 'placeholder'=>'Seleciona la fecha de la reunión','required']) !!}
                    </div>
                </div>

                <div class="col-sm-3">
                    <div class="form-group">
                        {!! Form::label('time_start','Hora inicio de la reunión:',['class'=>'form-label']) !!}
                        {!! Form::time('time_start', null, ['class'=>'form-control', 'placeholder'=>'Selecciona la hora de la reunión','required']) !!}
                    </div>
                </div>

                <div class="col-sm-3">
                    <div class="form-group">
                        {!! Form::label('time_end','Hora término de la reunión:',['class'=>'form-label']) !!}
                        {!! Form::time('time_end', null, ['class'=>'form-control', 'placeholder'=>'Selecciona la hora de término de la reunión','required']) !!}
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="form-group">
                        {!! Form::label('modality', 'Modalidad:', ['class'=>'form-label']) !!}
                        {!! Form::select('modality', $arrModalities, 1, ['class'=>'form-control picker select2', 'id'=>'modality_id', 'placeholder'=> 'Seleccione la Modalidad']) !!}
                    </div>
                </div>

                <div class="col-sm-4" id="div-place">
                    <div class="form-group">
                        {!! Form::label('place', 'Lugar de la reunión:', ['class'=>'form-label']) !!}
                        <a href="{{ route('places.create') }}" title="Agregar lugar"><i class="mdi mdi-map-marker-plus"></i></a>
                        {{-- Form::select('place', $arrPlaces, null, ['class'=>'form-control picker select2', 'placeholder'=>'Escribe el lugar de la reunión','required']) --}}

                        <select name="place" id="place" class="form-control picker select2">
                            <option selected="selected" value="">Escribe el lugar de la reunión</option>
                            @foreach ($arrPlaces as $item)
                                <option title="{{ $item->address}}" value="{{ $item->id}}" data-address="{{ $item->address}}"> {{ $item->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-sm-4" id="div-link">
                    <div class="form-group">
                        {!! Form::label('link', 'Link de la Reunión:', ['class'=>'form-label']) !!}
                        {!! Form::text('link', null, ['class'=>'form-control', 'placeholder'=>'Escribe el link de la reunión','required']) !!}
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="form-group">
                        {!! Form::label('files', 'Selecciona archivos adjuntos:', ['class'=>'form-label']) !!}<br />
                        {!! Form::file('files[]', null, ['class'=>'form-control', 'multiple' => 'multiple', 'placeholder'=>'Selecciona archivos adjuntos','required']) !!}
                    </div>
                </div>        
            </div>
        </div>

        <div class="card-footer">
            <div class="text-right">
                <a href="{{ route('meetings.index') }}" type="button" class="btn btn-default">Regresar</a>
                {!! Form::submit('Guardar', ['class'=>'btn btn-info']) !!}
            </div>
        </div>
    </div>
    {!! Form::close() !!}

    <div class="modal fade" id="mi-modal">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel">Confirmar nueva reunión</h5>
                </div>
                <div class="modal-body">
                    <p id="body-text"></p>
                    <div class="form-group">
                        <div class="progress">
                            <div class="progress-bar progress-bar-striped progress-bar-animated bg-danger" id="progress-bar-value" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <small id="progress-text"></small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" id="modal-btn-si">Si</button>
                    <button type="button" class="btn btn-primary" id="modal-btn-no">No</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push("scripts")
<script>
    function setPlacesForModalities(){
        
        let modality_id = $("#modality_id").val();
        
        switch (parseInt(modality_id)) {
            case 1:
                $("#div-place").show();
                $("#div-link").hide();
            break;
                
            case 2:
                $("#div-link").show();
                $("#div-place").hide();
            break;
            
            case 3:
                $("#div-link").show();
                $("#div-place").show();
            break;
        }
    }

    function progressBar(porcentaje, text) {
        $('.progress .progress-bar').css("width", porcentaje+'%', function() {
            $(this).attr("aria-valuenow", porcentaje) + "%";
        });
        $("#progress-bar-value").html(porcentaje + "%") ;
        $("#progress-text").html(text) ;
        
    }

    function generar_reunion(form) {
        let xhttp = new XMLHttpRequest();

        xhttp.upload.addEventListener("progress", (event) => {
            let porcentaje = Math.round((event.loaded / event.total) * 100);
            let text =  (porcentaje < 70 ? 'Cargando información de la reunión...': 
                        (porcentaje > 70 ? 'Enviando correo electrónico a los titulares de las instituciones...':''))
            progressBar(porcentaje >= 80 ? 80 : porcentaje, text);
        });

        xhttp.addEventListener("load", (res) => { 
            const DB = JSON.parse(res.srcElement.response);
            if(DB.saved == true) {
                progressBar(100);
                $("#progress-text").html(DB.message);
                setTimeout(function(){
                    let url_subject = '{{ route("meetings.subject", ":id") }}';
                    url_subject = url_subject.replace(':id', DB.meeting_id);

                    window.location.href = url_subject;
                }, 2000);
            } else {
                console.log(DB.message)
            }
        });

        xhttp.open('post', '{{ route('meetings.store') }}');
        xhttp.send(new FormData(form));
    }

    function formato(texto){
        return texto.replace(/^(\d{4})-(\d{2})-(\d{2})$/g,'$3/$2/$1');
    }

    document.addEventListener('DOMContentLoaded', () => {
        $('#modality_id').on('change', function () {
            setPlacesForModalities();
        });

        setPlacesForModalities();
    
        let form = document.getElementById('frmMeeting');
        form.addEventListener("submit", function (event) {
            event.preventDefault();

            if ($(this).valid()) {

                let meeting_type = $('select[name="meeting_type"] option:selected').text();
                let date = formato($('#date').val());
                let time_start = $('#time_start').val();
                let time_end = $('#time_end').val();

                let modality_id = $("#modality_id").val();
                let place = $('select[name="place"] option:selected').text();
                let link  = $('#link').val();
                let places = '';

                let address = $('select[name="place"] option:selected').data('address');

                switch (parseInt(modality_id)) {
                    case 1:
                        places = `${place} (${address})`;
                    break;
                        
                    case 2:
                       places = `através del link: ${link}`;
                    break;
                    
                    case 3:
                        places = `${place} (${address}) y através del link: ${link}`;
                    break;
                }

                const modalConfirm = function(callback){
                    $("#body-text").html(`Se creará una reunión de tipo ${meeting_type} para el día ${date} de ${time_start} Hrs a ${time_start} Hrs, a celebrarse en ${places}`)
                    $("#mi-modal").modal('show');

                    $("#modal-btn-si").on("click", function(){
                        callback(true);
                        document.getElementById('modal-btn-si').disabled = true;
                        document.getElementById('modal-btn-no').disabled = true;
                    });
                    
                    $("#modal-btn-no").on("click", function(){
                        callback(false);
                        $("#mi-modal").modal('hide');
                    });
                };

                modalConfirm(function(confirm){
                    if(confirm){
                        //Acciones si el usuario confirma
                        generar_reunion(document.getElementById('frmMeeting'));
                    }
                });
            }
        });
    });
</script>
@endpush