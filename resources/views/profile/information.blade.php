<h5>Dirección</h5>

{!! Form::open(['route'=>'profile.information', 'method' => 'POST']) !!}




<p class="form-group">
    {!! Form::label('phone','Teléfono') !!}
    {!! Form::text('phone',$user->phone,['class'=>'form-control','maxlength'=>'13']) !!}
</p>

<p class="form-group">
    {!! Form::label('address','Dirección') !!}
    {!! Form::textarea('address',$user->address,['class'=>'form-control','rows'=>'3']) !!}
</p>
<p class="form-group">
    {!! Form::label('zip_code','Código Postal') !!}
    {!! Form::text('zip_code',$user->zip_code,['class'=>'form-control','maxlength'=>'5']) !!}
</p>




<p class="text-right">
    {!! Form::submit('Actualizar', ['class'=>'btn btn-primary']) !!}
</p>

{!! Form::close() !!}
