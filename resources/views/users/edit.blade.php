@extends('layout.default')

@section('title', 'Editar usuario')
@section('description', 'Actualiza los datos de un usuario específico')

@section('breadcrumbs')
    {{ Breadcrumbs::render('users.edit',$user) }}
@endsection

@push('css')
@endpush

@push('scripts')
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    {!! JsValidator::formRequest('App\Http\Requests\UserUpdateRequest', '#frmUser'); !!}
@endpush

@section('content')




    {!! Form::open(['route'=>['users.update',$user], 'method' => 'PUT', 'enctype'=>'multipart/form-data', 'autocomplete'=>'off', 'id' => 'frmUser']) !!}


    <div class="card">
        <div class="card-body">

            <div class="row g-3">

                <div class="col-sm-4">
                    <div class="form-group">
                        {!! Form::label('name','Nombre',['class'=>'form-label']) !!}
                        {!! Form::text('name', $user->name, ['class'=>'form-control', 'placeholder'=>'Escribe el nombre del usuario','required']) !!}
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="form-group">
                        {!! Form::label('email','Correo electrónico',['class'=>'form-label']) !!}
                        {!! Form::email('email', $user->email, ['class'=>'form-control', 'placeholder'=>'Escribe un correo electrónico','required','readonly']) !!}
                    </div>
                </div>

                {{--<div class="col-sm-4">
                    <div class="form-group">
                        <div class="form-check form-switch">
                            {!! Form::checkbox('is_signer', true, $user->is_signer ,['id'=>'is_signer','class'=>'form-check-input','role'=>'switch']) !!}
                            <label for="is_signer" class="form-check-label">
                                Es Firmante
                            </label>
                        </div>
                    </div>
                </div>--}}

                <div class="col-sm-4">
                    <div class="form-group">
                        {!! Form::label('phone','Teléfono', ['class'=>'form-label']) !!}
                        {!! Form::text('phone', $user->phone, ['class'=>'form-control', 'placeholder'=>'Escriba el teléfono']) !!}
                    </div>
                </div>

                <div class="col-12">
                    <p class="alert alert-info mb-0">
                        <i class="mdi mdi-information-outline mdi-18px"></i> <strong>Atención:</strong> Deje el campo
                        contraseña en blanco si no desea actualizarla.
                    </p>
                </div>

                <div class="col-sm-6">
                    <div class="form-group">
                        {!! Form::label('password','Contraseña',['class'=>'form-label']) !!}
                        {!! Form::password('password', ['class'=>'form-control', 'placeholder'=>'**************', 'autocomplete'=>'nope','readonly','onfocus'=>"this.removeAttribute('readonly')"]) !!}
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="form-group">
                        {!! Form::label('password_confirmation','Confirmar contraseña',['class'=>'form-label']) !!}
                        {!! Form::password('password_confirmation', ['class'=>'form-control', 'placeholder'=>'**************', 'autocomplete'=>'nope','readonly','onfocus'=>"this.removeAttribute('readonly')"]) !!}
                    </div>
                </div>

                <div class="col-sm-4 component-hidden-inverted">
                    {!! Form::label('entity_id','Institución',['class'=>'form-label']) !!}
                    <div class="form-row">
                        {!! Form::select('entity_id',$entities, old('entity_id', $user->entity_id) ?? null, ['class'=>'form-control picker select2','id'=>'entity_id', 'placeholder'=>'Seleccione Institución']) !!}
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="form-group">
                        {!! Form::label('job','Puesto',['class'=>'form-label']) !!}
                        {!! Form::text('job', $user->job, ['class'=>'form-control', 'placeholder'=>'Escribe el puesto']) !!}
                    </div>
                </div>

                @if(!Auth::user()->hasRole('Administrador Consultivo'))
                    <div class="col-sm-4 component-hidden-inverted">
                        {!! Form::label('role','Roles de usuario',['class'=>'form-label']) !!}
                        <div class="form-row">
                            <select multiple="multiple" name="role[]" id="roles" class="form-control select2">
                                @foreach($roles as $Role)
                                    <option value="{{$Role->id}}" @foreach($user->roles as $uRole) @if($Role->id == $uRole->id) selected="selected" @endif @if($Role->id != 1 && $uRole->id == 1) disabled="disabled" @endif @endforeach>{{$Role->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                @endif

                <div class="row g-3">
                    <div class="col-sm-1">
                        <img src="/images/avatars/{{ $user->avatar }}"
                             class="profile-image float-none rounded-circle mx-auto d-block img-fluid"/>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            {!! Form::label('avatar','Avatar',['class'=>'form-label']) !!}
                            {!! Form::file('avatar',['class'=>'form-control']) !!}
                        </div>
                    </div>
                </div>



            </div>


        </div>


        <div class="card-footer">
            <div class="text-end">
                <a href="{{ route('users.index') }}" type="button" class="btn btn-default">Regresar</a>
                {!! Form::submit('Editar', ['class'=>'btn btn-info']) !!}
            </div>
        </div>
    </div>


    {!! Form::close() !!}




@endsection


@push("css")
    {{-- <link rel="stylesheet" href="{{ asset('assets/plugins/select-picker/dist/picker.min.css') }}"> --}}
@endpush

@push("scripts")
    {{-- <script src="{{ asset('assets/plugins/select-picker/dist/picker.min.js') }}"></script> --}}

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




