<h5>Actualizar contraseña (Opcional)</h5>


{!! Form::open(['route'=>'profile.password', 'method' => 'POST']) !!}
<div class="row g-3">

    <div class="col-sm-6">
        <div class="form-group">
            {!! Form::label('password','Contraseña',['class'=>'form-label']) !!}
            {!! Form::password('password', ['class'=>'form-control', 'placeholder'=>'**************']) !!}
        </div>
    </div>

    <div class="col-sm-6">
        <div class="form-group">
            {!! Form::label('password_confirmation','Confirmar Contraseña',['class'=>'form-label']) !!}
            {!! Form::password('password_confirmation', ['class'=>'form-control', 'placeholder'=>'**************']) !!}
        </div>
    </div>

    <div class="col-sm-12">
        <p class="text-right">
            {!! Form::submit('Actualizar', ['class'=>'btn btn-primary']) !!}
        </p>
    </div>
</div>

{!! Form::close() !!}
