@extends('layout.default')

@section('title', 'Crear menú')
@section('description', '')

@section('breadcrumbs')
    {{ Breadcrumbs::render('menus.create') }}
@endsection

@push('css')

@endpush

@push('scripts')
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    {!! JsValidator::formRequest('App\Http\Requests\MenuCreateRequest', '#formMenus'); !!}
@endpush

@section('content')


    {!! Form::open(['route'=>'menus.store', 'method' => 'POST', 'id' => 'formMenus']) !!}

    <div class="card">
        <div class="card-body">

            <div class="row g-3">
                <div class="col-sm-4">
                    <div class="form-group">
                        {!! Form::label('name','Nombre',['class'=>'form-label']) !!}
                        {!! Form::text('name', null, ['class'=>'form-control', 'placeholder'=>'Escribe el nombre del menú','required']) !!}
                    </div>
                </div>

                <div class="col-sm-4">
                    {!! Form::label('category_id','Categoría',['class'=>'form-label']) !!}
                    <div class="form-row">
                        {!! Form::select('category_id', $category, old('category_id') ?? '', ['class'=>'form-control select2','id'=>'category', 'data-placeholder'=>'Buscar por categoría', 'placeholder'=>'']) !!}
                    </div>
                </div>

                <div class="col-sm-4">
                    {!! Form::label('icon_id','Icono',['class'=>'form-label']) !!}
                    <div class="form-row">
                        {!! Form::select('icon_id', [], null, ['class'=>'form-control selectIcon ','id'=>'icon_id','placeholder'=>'Seleccione un icono']) !!}
                    </div>
                </div>
                <div class="col-sm-4">
                    {!! Form::label('dropdown','Seleccione si es un elemento desplegable',['class'=>'form-label']) !!}
                    <div class="form-check form-switch">
                        <input type="checkbox" class="form-check-input" id="dropdown" name="dropdown"  {{old('dropdown') ?  'checked' : '' }}>
                        <label class="form-check-label" for="dropdown">Es desplegable</label>
                      </div>
                </div>
                <div class="col-sm-6 component-hidden-inverted">
                    {!! Form::label('permission_id','Permiso',['class'=>'form-label']) !!}
                    <div class="form-row">
                        {!! Form::select('permission_id', $permission, old('permission_id') ?? null, ['class'=>'form-control select2','id'=>'permission','data-placeholder'=>'Seleccione por permisos', 'placeholder'=>'']) !!}
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group">
                        {!! Form::label('order','Orden',['class'=>'form-label']) !!}
                        {!! Form::number('order', null, ['class'=>'form-control', 'placeholder'=>'Escribe su orden en el menú']) !!}
                    </div>
                </div>
                <div class="col-sm-4">
                    {!! Form::label('published','Seleccione si es un elemento que estará publicado',['class'=>'form-label']) !!}
                    <div class="form-check form-switch">
                      {{old('published')}}
                        <input type="checkbox" class="form-check-input" id="published" name="published" {{  old('published') ? 'checked' : '' }} >
                        <label class="form-check-label" for="published">Publicar</label>
                      </div>
                </div>


                @include('menus.partial._children')


            </div>
        </div>

        <div class="card-footer">
            <div class="text-right">
                <a href="{{ route('menus.index') }}" type="button" class="btn btn-default">Regresar</a>
                {!! Form::submit('Guardar', ['class'=>'btn btn-info']) !!}
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
        $(function() {



            $('.selectIcon').select2({
                theme: 'bootstrap-5',
                allowClear: true,
                //multiple: true,
                language: 'es',
                //templateResult: formatList,
                escapeMarkup: function(markup) {
                    return markup;
                },
                templateResult: function(data) {
                    return data.html;
                },
                templateSelection: function(data) {
                    return data.text;
                },
                placeholder: 'Buscar un icono por nombre',
                //minimumInputLength: 1,
                ajax: {
                    url: '/menu/ajaxIcon',
                    dataType: 'json',
                    method: 'POST',
                    delay: 250,
                    data: function (params) {
                        return {
                            term: params.term
                        }
                    },
                    processResults: function (data, page) {
                        return {
                            results: data
                        };
                    },
                },
            });
        });
    </script>


@endpush


