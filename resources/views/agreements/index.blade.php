@extends('layout.default')



@section('title', 'Seguimiento de acuerdos')


@section('breadcrumbs')
    {{ Breadcrumbs::render('agreements.index') }}
@endsection

@section('content')

            <div class="card card-body mb-3">
                {!! Form::open(['route'=>'agreements.index', 'method' => 'POST', 'autocomplete'=>'off']) !!}
                <div class="row g-3">
                    <div class="col-md-5 col-sm-12">
                        <div class="input-group input-daterange datepicker p-0" id="datepicker">
                            <span class="input-group-text">Fecha</span>
                            <input type="text" class="form-control" name="start_date" placeholder="Desde" value="{{ $request->start_date ?? '' }}"  />
                            <span class="input-group-text"><span>a</span></span>
                            <input type="text" class="form-control" name="end_date" placeholder="Hasta" value="{{ $request->end_date ?? '' }}"  />

                        </div>
                    </div>
                    @if(!Auth::user()->hasRole(Helper::$roleEntidad ))
                        <div class="col-md-3 col-sm-12">
                            {!! Form::select('meeting_type_id', $meeting_types, $request->meeting_type_id, ['class'=>'form-control picker select2','placeholder' => '','id'=>'meeting_type_id','data-placeholder' => 'Seleccionar tipo de reunión']) !!}
                        </div>
                    
                        <div class="col-md-2 col-sm-12">
                            {!! Form::select('entity_id', [], [], ['class'=>'form-control picker select2','placeholder' => '','id'=>'entity_id','data-placeholder' => 'Seleccionar institución']) !!}
                        </div>
                    @else
                        <div class="col-md-5 col-sm-12">
                            {!! Form::select('meeting_type_id', Auth::user()->entity->meeting_types->pluck('name', 'id'), $request->meeting_type_id, ['class'=>'form-control picker select2','placeholder' => '','id'=>'meeting_type_id','data-placeholder' => 'Seleccionar tipo de reunión']) !!}
                        </div>
                    @endif
                    <div class="col-md-2 col-sm-12">
                        <div class="form-group">
                            {!! Form::select('meeting_id', [], $request->meeting_id ?? [], ['class'=>'form-control picker select2', 'id'=>'meeting_id', 'placeholder'=> '' , 'data-placeholder' => 'Seleccione reunión']) !!}
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-12">
                        <div class="form-group">
                            {!! Form::select('status_id', $status, $request->status_id ?? [], ['class'=>'form-control picker select2', 'id'=>'status_id', 'placeholder'=> '' , 'data-placeholder' => 'Seleccione estatus']) !!}
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-12">
                        {!! Form::text('agreement', $request->agreement, ['class'=>'form-control', 'placeholder'=>'Buscar por acuerdo']) !!}
                    </div>
                    <div class="card-footer">
                    <div class="text-end">
                        <a href="{{ route('agreements.index') }}" class="btn btn-default">Restablecer</a>
                        {!! Form::submit('Filtrar', ['class'=>'btn btn-info']) !!}
                    </div>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
                <div class="row">
                    @foreach ($agreements as $agreement)
                        <div class="col-lg-4 col-sm-12 mb-2">
                            <div class="card shadow-sm">
                                <div class="card-body pb-0">
                                    <blockquote class="blockquote blockquote-custom bg-transparent">
                                    <div class="row g-0">
                                        <div class="col-lg-12 col-sm-12">
                                            <div class="row border-bottom">
                                                <div class="col-6">
                                                    <div class="card-title bg-transparent">{{ $agreement->meeting->meeting_type->acronym}} {{date("d/m/Y", strtotime($agreement->meeting->meeting_date))}}</div>
                                                </div>
                                                <div class="col-6 text-end">
                                                    <div class="bg-transparent text-{{$agreement->status->color}}"><span class="h5" title="Avance general">{{$agreement->actions_count>0?round((($agreement->actions_c+($agreement->actions_p/2??0))/$agreement->actions_count)*100,2):0}}%</span></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 col-sm-12 border-bottom mb-2">
                                            <p class="mb-0 mt-2 fs-14px card-text" data-toggle="tooltip" data-placement="top" title="{{strlen($agreement->agreement)>239?$agreement->agreement:''}}">
                                                {{$agreement->agreement}}
                                            </p>
                                        </div>
                                        <div class="col-lg-12 col-sm-12">
                                            <footer>
                                                <div class="row">
                                                    <div class="col-lg-12 col-12 text-end">
                                                        @can('Consultar Acuerdos')
                                                            <a data-agreement_id="{{$agreement->id}}"
                                                                class="btn btn-icon btn-info btn-detail" data-toggle="tooltip" data-placement="top" title="Avance por institución"
                                                                data-bs-toggle="modal" data-bs-target="#entities_detail">
                                                                    <i class="mdi mdi-clipboard-text-outline"></i>
                                                            </a>
                                                        @endcan
                                                        @can('Crear Avances')
                                                            <a href="{{ route('agreements.reply', $agreement->id) }}" class="btn btn-icon btn-warning" data-toggle="tooltip" data-placement="top" title="Dar avance">
                                                                <i class="mdi mdi-file-document-edit"></i>
                                                            </a>
                                                        @endcan
                                                    </div>
                                                </div>
                                            </footer>
                                        </div>
                                    </div>
                                    </blockquote>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            <div class="d-flex justify-content-center">
                {!! $agreements->appends(request()->except(['page','_token']))->render() !!}
            </div>
<div class="modal fade" id="entities_detail">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Avance por institución</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="entities_detail_content">

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-bs-dismiss="modal">Cerrar</button>

            </div>
        </div>
    </div>
</div>
@endsection
@push('css')
<style>
    .blockquote-custom {
    position: relative;
    font-size: 0.8rem;
    }

    .blockquote-custom-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    position: absolute;
    top: -30px;
    left: 5px;
    }
    .card-text{
        height: 110px;display: -webkit-box;
        -webkit-line-clamp: 5;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>
@endpush
@push('scripts')
    <script>
        $(document).ready(function() {
            loadInstitutions();
            loadMeetings();
        });
        function loadMeetings(){
            let type = $("#meeting_type_id").val();
            let selectedValue = "{{$request->meeting_id}}";
            var combo = $('select[name="meeting_id"]');
            if (type) {
                $.post("{{route('meetings.ajaxgetforcatalog')}}", {id: type})
                    .done(function (data) {
                        let entries = Object.entries(data);
                        let sorted = entries.sort((a,b) => (a[1] > b[1]) ? 1 : ((b[1] > a[1]) ? -1 : 0));
                        combo.empty();
                        combo.append('<option value="">Seleccione Reunión</option>');
                        jQuery.each(sorted, function (key, value) {
                            var selected = '';
                            if(selectedValue == value[0])
                                selected = 'selected';
                            combo.append('<option value="' + value[0] + '" ' + selected + '>' + value[1] + '</option>');
                        });

                    }).fail(function (data) {
                }).always(function () {
                });
            } else {
                combo.empty();
                combo.append('<option value="">Seleccione Reunión</option>');
                combo.trigger("change");
            }
        }
        $('#meeting_type_id').on('change', function () {
            loadMeetings();
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
        $('.btn-detail').click(function () {
            console.log($(this).data("agreement_id"));
            LoadigShow();
            $.post("{{ route('agreements.entities.detail') }}", { agreement_id: $(this).data("agreement_id") })
                .done(function (data) {
                    $('#entities_detail_content').html(data);
                }).fail(function (data) {
                console.log(data);
            }).always(function () {
                LoadingHide(300);
            });
        });
    </script>
@endpush