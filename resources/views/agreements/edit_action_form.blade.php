{!! Form::open(['route'=>['agreements.action.update',$action->id], 'method' => 'PUT', 'id' => 'frmEditAction']) !!}
{!! Form::hidden('agreement_id', $action->agreement_id) !!}
<div class="card">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-sm-12">
                <div class="form-group">
                    {!! Form::label('action', 'Acción:', ['class'=>'form-label']) !!}
                    {!! Form::textarea('action', $action->action, ['class'=>'form-control', 'rows' => 2, 'placeholder'=>'Escribe la acción','required']) !!}
                </div>
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
@push("scripts")
<script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
{!! JsValidator::formRequest('App\Http\Requests\ActionCreateRequest', '#frmEditAction'); !!}
@endpush