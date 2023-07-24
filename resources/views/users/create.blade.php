@extends('layout.default')

@section('title', 'Crear usuario')
@section('description', 'Crea usuario y asigna roles de acceso')

@section('breadcrumbs')
    {{ Breadcrumbs::render('users.create') }}
@endsection

@section('content')

    {!! Form::open(['route'=>'users.store', 'method' => 'POST', 'enctype'=>'multipart/form-data', 'id' => 'frmUser']) !!}

    <div class="card">
        <div class="card-body">

            <div class="row g-3">
                <div class="col-sm-4">
                    <div class="form-group">
                        {!! Form::label('name','Nombre', ['class'=>'form-label']) !!}
                        {!! Form::text('name', null, ['class'=>'form-control', 'placeholder'=>'Escribe el nombre del usuario','required']) !!}
                    </div>
                </div>


                <div class="col-sm-4">
                    <div class="form-group">
                        {!! Form::label('email','Correo electrónico', ['class'=>'form-label']) !!}
                        {!! Form::email('email', null, ['class'=>'form-control', 'placeholder'=>'Escribe un correo electrónico','required']) !!}
                    </div>
                </div>

                {{--<div class="col-sm-4">
                    <div class="form-group">
                        <div class="form-check form-switch">
                            {!! Form::checkbox('is_signer', true,  false ,['id'=>'is_signer','class'=>'form-check-input','role'=>'switch']) !!}
                            <label for="is_signer" class="form-check-label">
                                Es Firmante
                            </label>
                        </div>
                    </div>
                </div>--}}

                <div class="col-sm-4">
                    <div class="form-group">
                        {!! Form::label('phone','Teléfono', ['class'=>'form-label']) !!}
                        {!! Form::text('phone', null, ['class'=>'form-control', 'placeholder'=>'Escribe el teléfono']) !!}
                    </div>
                </div>



                <div class="col-sm-4">
                    <div class="form-group">
                        {!! Form::label('password','Contraseña', ['class'=>'form-label']) !!}
                        {!! Form::password('password', ['class'=>'form-control', 'placeholder'=>'**************','required']) !!}
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="form-group">
                        {!! Form::label('password_confirmation','Confirmar contraseña', ['class'=>'form-label']) !!}
                        {!! Form::password('password_confirmation', ['class'=>'form-control', 'placeholder'=>'**************','required']) !!}
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="form-group">
                        {!! Form::label('job','Puesto', ['class'=>'form-label']) !!}
                        {!! Form::text('job', null, ['class'=>'form-control', 'placeholder'=>'Escribe el puesto']) !!}
                    </div>
                </div>

                <div class="col-sm-4 component-hidden-inverted">
                    {!! Form::label('role','Roles de usuario',['class'=>'form-label']) !!}
                    <div class="form-row">
                        <select multiple="multiple" name="role[]" id="roles" class="form-control select2">
                            @foreach($roles as $Role)
                                <option value="{{$Role->id}}" >{{$Role->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div id="div-entidades" class="col-sm-4 component-hidden-inverted" hidden>
                    {!! Form::label('entity_id','Institución',['class'=>'form-label']) !!}
                    <div class="form-row">
                        {!! Form::select('entity_id',$entities, old('entity_id') ?? [], ['class'=>'form-control picker select2','id'=>'entity_id', 'placeholder'=>'Seleccione Institución']) !!}
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-sm-6">
                        <div class="form-group">
                            {!! Form::label('avatar','Avatar', ['class'=>'form-label']) !!}
                            {!! Form::file('avatar',['class'=>'form-control']) !!}
                        </div>
                    </div>
                </div>


            </div>
        </div>

        <div class="card-footer">
            <div class="text-end">
                <a href="{{ route('users.index') }}" type="button" class="btn btn-default">Regresar</a>
                {!! Form::submit('Registrar', ['class'=>'btn btn-info']) !!}
            </div>
        </div>

    </div>

    {!! Form::close() !!}

@endsection

@push("css")
    {{-- <link rel="stylesheet" href="{{ asset('assets/plugins/select-picker/dist/picker.min.css') }}"> --}}
@endpush

@push("scripts")
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    {!! JsValidator::formRequest('App\Http\Requests\UserCreateRequest', '#frmUser'); !!}

    <script>
        $(function () {

             $('#roles').on('change', function(e) {
                var options = $(this)
                var option = $('option', options);


                if (options.val().length > '1' && options.val().includes('1')) {
                   $(this).val(['1']).trigger('change');
                } 

                if (options.val().includes('1')) {
                  option.each(function() {
                      if (this.value != 1) {
                        $(this).attr('disabled', 'disabled');
                      }
                   })
                } else {
                  option.each(function() {
                      $(this).removeAttr('disabled');
                   })
                }

                if (options.val().includes('2')) {
                  $("#div-entidades").prop('hidden',false);
                } else {
                  $("#div-entidades").prop('hidden',true);
                }
            });
        });
    </script>


@endpush
