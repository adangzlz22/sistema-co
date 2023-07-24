{!! Form::open(['route'=>'agreements.reply.action.store', 'method' => 'POST', 'id' => 'frmReplyAction', 'files' => true ]) !!}
{!! Form::hidden('agreement_id', $action->agreement_id) !!}
{!! Form::hidden('action_id', $action->id) !!}
<div class="card">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-sm-12">
                <p class="fs-14px">
                    {{$action->action}}
                </p>
                <hr>
                <div class="form-group">
                    {!! Form::label('reply', 'Avance:', ['class'=>'form-label']) !!}
                    {!! Form::textarea('reply', null, ['class'=>'form-control', 'rows' => 2, 'placeholder'=>'Escribe un detalle de avance no mayor a 500 caracteres','required','maxlength'=>500]) !!}
                </div>
                <br>
                <div class="form-group">
                    <div class="form-check form-check-inline">
                        <input 
                            class="form-check-input" 
                            type="checkbox" 
                            id="status_id" 
                            name="status_id"
                            onchange="$(this).is(':checked')?notify('Advertencia', 'Está a punto de cambiar el estatus de la acción', 'Justo ahora'):null"
                        />
                        <label class="form-check-label" for="status_id" data-toggle="tooltip" data-placement="top">{{$status}}</label>
                    </div>
                    <p class="text-red">*Selecciona este campo para cambiar el estatus de la acción*</p>
                </div>
                <div class="form-group text-end">
                    <label for="files"
                        class="btn btn-icon btn-warning" data-toggle="tooltip" data-placement="top" title="Adjuntar archivos">
                            <i class="mdi mdi-paperclip"></i>
                    </label>
                    <input hidden class="custom-files" type="file" id="files" name="files[]" multiple data-multiple-caption="{count} archivos seleccionados"/>
                </div>
            </div>
            <div class="col-sm-12">
                <span class="badge rounded-pill bg-warning fs-12px"></span>
            </div>
        </div>
    </div>

    <div class="card-footer">
        <div class="text-right">
            {!! Form::submit('Guardar', ['class'=>'btn btn-info']) !!}
        </div>
    </div>
</div>
{!! Form::close() !!}
<script>
    customfilecount();
</script>
@push("scripts")
<script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
{!! JsValidator::formRequest('App\Http\Requests\ReplyActionCreateRequest', '#frmReplyAction'); !!}
@endpush