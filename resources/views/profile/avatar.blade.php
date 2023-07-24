<div class="row g-3">
    <div class="col-sm-2">
        <img src="/images/avatars/{{ $user->avatar }}" class="rounded-circle" style="width: 5rem"/>
    </div>
    <div class="col-sm-10">
        <h5 class="mb-1">Mi Perfil</h5>
        <p>{{ $user->email }} </p>


        {!! Form::open(['route'=>'profile.avatar', 'method' => 'POST', 'enctype'=>'multipart/form-data', 'accept'=>'.jpg, .png, .jpeg']) !!}

        <p class="form-group">
            {!! Form::label('name','Nombre',['class'=>'form-label']) !!}
            {!! Form::text('name',$user->name, ['class'=>'form-control']) !!}
        </p>
        <p class="form-group">
            {!! Form::label('phone','Teléfono',['class'=>'form-label']) !!}
            {!! Form::text('phone',$user->phone, ['class'=>'form-control']) !!}
        </p>

        <p class="form-group">
            {!! Form::label('avatar','Actualizar imagen de perfil (opcional)',['class'=>'form-label']) !!}
            {!! Form::file('avatar',['class'=>'form-control']) !!}
        </p>

        <p class="text-right">
            {!! Form::submit('Actualizar', ['class'=>'btn btn-primary']) !!}
        </p>

        {!! Form::close() !!}
    </div>
</div>
