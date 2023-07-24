@extends('layout.default')

@section('title','Calendario de reuniones')

@section('breadcrumbs')
    {{ Breadcrumbs::render('calendar.index') }}
@endsection


@section('content')
    <hr>
    <div class="card card-body mb-3">
        {!! Form::open(['route'=>'calendar.index', 'method' => 'POST', 'autocomplete'=>'off']) !!}
        <div class="row g-3">

            @if(!Auth::user()->hasRole(Helper::$roleEntidad ))
                <div class="col-md-3 col-sm-12">
                    {!! Form::select('meeting_type_id', $meeting_types, $request->meeting_type_id, ['class'=>'form-control select2','placeholder' => '','id'=>'meeting_type_id','data-placeholder' => 'Seleccionar tipo de reunión']) !!}
                </div>
            
                <div class="col-md-3 col-sm-12">
                    {!! Form::select('entity_id', [], [], ['class'=>'form-control select2','placeholder' => '','id'=>'entity_id','data-placeholder' => 'Seleccionar institución']) !!}
                </div>
            @else
                <div class="col-md-6 col-sm-12">
                    {!! Form::select('meeting_type_id', Auth::user()->entity->meeting_types->pluck('name', 'id'), $request->meeting_type_id, ['class'=>'form-control select2','placeholder' => '','id'=>'meeting_type_id','data-placeholder' => 'Seleccionar tipo de reunión']) !!}
                </div>
            @endif
            <div class="col-md-3 col-sm-12">
                {!! Form::select('status_id', $status, $request->status_id, ['class'=>'form-control select2','placeholder' => '','id'=>'status_id','data-placeholder' => 'Seleccionar estatus']) !!}
            </div>
            <div class="col-md-3 col-sm-12 text-end">
                <a href="{{ route('calendar.index') }}" class="btn btn-default">Restablecer</a>
                {!! Form::submit('Filtrar', ['class'=>'btn btn-info']) !!}
            </div>

        </div>
        {!! Form::close() !!}
    </div>

    @php



            @endphp
    <div class="row mt-3 g-1">
        <div class="col-md-8 col-sm-12">
            <div id="calendar" class="card card-body"></div>
        </div>
        <div class="col-md-4 col-sm-12">
            <div id="calendar_list" class="card card-body"></div>
        </div>
    </div>
        @endsection

    @push("scripts")
    <style>
        .fc-toolbar {
            text-transform: capitalize;
        }
        .fc-button-primary{
            background-color: #FFFFFF !important;
            border-color: #9B2F3E !important;
            color: #9B2F3E !important;
        }
    </style>
    @endpush
    @push('scripts')
    <script>
        $(document).ready(function() {
            loadInstitutions();
        });

        var meetings = {!! $meetings !!};
        console.log(meetings);
        var events= meetings.map(x=>(
            {
                id:x.id,
                groupId:x.meeting_type_id,
                title: x.meeting_type.name,
                start: x.meeting_date+' '+x.meeting_time,
                end: x.meeting_date+' '+x.meeting_time_end,
                backgroundColor:x.meeting_type.color,
                borderColor:x.meeting_type.color,
                textColor:'white',
                meeting_type: x.meeting_type.name,
                place: x.place,
                link: x.link,
                modality: x.modality.name,
                status: x.status.name,
                status_color: x.status.color,
            }
        ));
        var handleEventDidMount = (info)=> {
                let ev=info.event;
                let extp=info.event.extendedProps;
                tippy(info.el, {
                    placement: 'bottom',
                    interactive: true,
                    maxWidth: 350,
                    content: `
                        <div class="bg-transparent">
                        <h6 class="card-title">${extp.meeting_type}</h6>
                        ${extp.modality=="VIRTUAL"?'<i class="mdi mdi-24px mdi-video-account"></i>':
                            (extp.modality=="MIXTA"?'<i class="mdi mdi-24px mdi-office-building-marker-outline"></i><i class="mdi mdi-24px mdi-video-account"></i>':
                            '<i class="mdi mdi-24px mdi-office-building-marker-outline"></i>')}
                        <br/>
                        ${extp.modality != "VIRTUAL" && extp.place.name!=null?`La reunión será en <strong>${extp.place.name},${extp.place.address}</strong>.<br/>`:""}
                        ${extp.modality != "PRESENCIAL" && extp.link!=null?`Da clic al enlace para entrar a la reunión virtual.<br/>
                        <a href="${extp.link}" target="_blank" class="link-bg-dark">Ir a reunión virtual</a>`:""}
                        </p>
                        </div>
                    `,
                    allowHTML: true,
                });
            }
        var handleRenderFullcalendar = function() {
            var d = new Date();
            var month = d.getMonth() + 1;
                month = (month < 10) ? '0' + month : month;
            var year = d.getFullYear();
            var day = d.getDate();
            var today = moment().startOf('day');

            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                allDaySlot:false,
                locale: 'es',
                titleFormat:{ year: 'numeric', month: 'short'},
                headerToolbar: {
                    center: 'dayGridMonth,timeGridWeek,timeGridDay',
                    left: 'title',
                    right: 'prev,next today'
                },
                buttonText: {
                    today:    'Hoy',
                    month:    'Mes',
                    week:     'Semana',
                    day:      'Día',
                },
            initialView: 'dayGridMonth',
            editable: false,
            droppable: false,
            themeSystem: 'bootstrap',
            businessHours: {
                // days of week. an array of zero-based day of week integers (0=Sunday)
                daysOfWeek: [ 1, 2, 3, 4, 5], // Monday - Friday

                startTime: '8:00', 
                endTime: '16:00', 
            },
            eventDidMount: handleEventDidMount,
            events: events,
            eventTimeFormat: { 
                hour: '2-digit',
                minute: '2-digit',
                // second: '2-digit',
                meridiem: false,
                hour12: false
            },
            });
            calendar.render();

            var calendarListEl = document.getElementById('calendar_list');
            var calendarList = new FullCalendar.Calendar(calendarListEl, {
                locale: 'es',
                titleFormat:{ day: 'numeric', month: 'short'},
                headerToolbar: {
                    left: 'title',
                    right: 'prev,next today'
                },
                buttonText: {
                    today:    'Semana actual',
                    listWeek: 'Lista'
                },
            initialView: 'listWeek',
            editable: false,
            droppable: false,
            themeSystem: 'bootstrap',
            events: events,
            eventTimeFormat: { 
                hour: '2-digit',
                minute: '2-digit',
                // second: '2-digit',
                meridiem: false,
                hour12: false
            },
            eventDidMount: handleEventDidMount,
            });
            calendarList.render();

            calendar.on('eventClick', function(info) {
                let ev=info.event;
                let route = "{{ Auth::user()->entity?route('agreements.index'):route('meetings.subject', ':id') }}"
                route = route.replace(':id', ev._def.publicId);
                window.location = route;
            });
            calendarList.on('eventClick', function(info) {
                let ev=info.event;
                let route = "{{ Auth::user()->entity?route('agreements.index'):route('meetings.subject', ':id') }}"
                route = route.replace(':id', ev._def.publicId);
                window.location = route;
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            handleRenderFullcalendar();
        });

        function loadInstitutions(){
            let type = $("#meeting_type_id").val();
            let selectedValue = "{{$request->entity_id}}";
            var combo = $('select[name="entity_id"]');
            // if (type) {
                $.post("{{route('entities.ajaxgetforcatalog')}}", {meeting_type_id: type})
                    .done(function (data) {
                        let entries = Object.entries(data);
                        let sorted = entries.sort((a,b) => (a[1] > b[1]) ? 1 : ((b[1] > a[1]) ? -1 : 0));
                        combo.empty();
                        combo.append('<option value="">Seleccione Institución</option>');
                        jQuery.each(sorted, function (key, value) {
                            var selected = '';
                            if(selectedValue == value[0])
                                selected = 'selected';
                            combo.append('<option value="' + value[0] + '" ' + selected + '>' + value[1] + '</option>');
                        });

                    }).fail(function (data) {
                }).always(function () {
                });
            // } else {
            //     combo.empty();
            //     combo.append('<option value="">Seleccione Institucion</option>');
            //     combo.trigger("change");
            // }
        }
        $('#meeting_type_id').on('change', function () {
            loadInstitutions();
        });
    </script>
@endpush
