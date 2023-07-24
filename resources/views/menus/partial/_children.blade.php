<div class="component-hidden">
    <hr/>
    <div class="col-xl-12">
            <div class="card mb-3">
            <div class="card-header d-flex">
                <span class="flex-grow-1">Sub menus</span>
                {!! Form::button('<i class="mdi mdi-format-list-bulleted"></i> Asignar sub menus', ['class'=>'btn btn-sm btn-info mb-2','data-bs-toggle'=> 'modal', 'data-bs-target'=>'#newMenuModal', 'id' => 'openModal']) !!}
            </div>
            <div class="card-body">
                <table class="table table-striped table-sm" id="table_children">
                    <thead>
                    <th>Nombre</th>
                    <th>Permiso</th>
                    <th>Orden</th>
                    <th>Publicado</th>
                    <th>Acciones</th>
                    </thead>
                    <tbody>
                        @if(old('row') || isset($sons))
                            @foreach (old('row') ?? $sons ?? null as  $i => $item)

                                <tr>
                                    <td>{{ $item['name'] ?? ''}}</td>
                                    <td data-id="{{ $item['permission_id'] ?? '' }}" >{{ $item['permission_name'] ?? $item->permission->name ?? '' }}</td>
                                    <td>{{ $item['order'] ?? ''}}</td>
                                    <td data-id="{{ $item['published'] ?? '' }}">{!! $item['published']  ? '<span class="badge bg-success">Publicado</span>' : '<span class="badge bg-secondary">No publicado</span>'!!}</td>
                                    <td>
                                        <a href="#!" class="btn btn-icon btn-warning btn-modify" >
                                            <i class="mdi mdi-file-document-edit"></i>
                                        </a>
                                        <a data-message="¿Esta seguro de eliminar este registro?" ref="#!" class="btn btn-icon btn-danger btn-confirm-custom">
                                            <i class="mdi mdi-trash-can"></i>
                                        </a>
                                    </td>
                                    <td hidden>
                                        <input type="hidden" name="row[{{$i}}][name]" value="{{ $item['name'] ?? ''}}"/>
                                        <input type="hidden" name="row[{{$i}}][permission_id]" value="{{ $item['permission_id'] ?? '' }}"/>
                                        <input type="hidden" name="row[{{$i}}][order]" value="{{ $item['order'] ?? ''}}"/>
                                        <input type="hidden" name="row[{{$i}}][permission_name]" value="{{ $item['permission_name'] ?? $item->permission->name ?? ''}}"/>
                                        <input type="hidden" name="row[{{$i}}][published]" value="{{ $item['published'] ?? ''}}"/>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="newMenuModal">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><label id="title_child"></label> opción hijo del menú</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                        <div class="row g-3">
                            <div class="alert alert-danger" id="alert_child">
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                <strong>Error!</strong> El nombre, permiso son requeridos.
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    {!! Form::label('name_child','Nombre',['class'=>'form-label ']) !!}
                                    {!! Form::text('name_child', null, ['class'=>'form-control child', 'placeholder'=>'Escribe el nombre del menú']) !!}
                                </div>
                            </div>
                            <div class="col-sm-4">
                                {!! Form::label('permission_child','Permiso',['class'=>'form-label']) !!}
                                <div class="form-row">
                                    {!! Form::select('permission_child', $permission, '', ['class'=>'form-control picker child','id'=>'permission_child', 'placeholder'=>'Buscar por permisos']) !!}

                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    {!! Form::label('order_child','Orden',['class'=>'form-label']) !!}
                                    {!! Form::number('order_child', null, ['class'=>'form-control child', 'placeholder'=>'Escribe el orden que tendra en el menú']) !!}
                                </div>
                            </div>
                            <div class="col-sm-2">
                                {!! Form::label('published_cild','Publicado',['class'=>'form-label']) !!}
                                <div class="form-check form-switch">
                                    <input type="checkbox" class="form-check-input" id="published_child" name="published_child" checked>
                                    <label class="form-check-label" for="published_child">Publicar</label>
                                  </div>
                            </div>
                        </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-info" id="btnAdd"><label id="title_btn_child">Agregar</label></button>
                </div>
          </div>

        </div>
      </div>
</div>




@push('scripts')


{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script> --}}
    <script type="text/javascript">

        var _row = null;
        $(document).ready(function(){

            var initialValue = {{ old('icon_id')  ?? $model->icon_id ?? 0 }};

            if(initialValue != 0)
                initValue("#icon_id",initialValue);
            function initValue(element,id){
                var name = "";
                var newOption="";
                /*$.post('{{ route('menus.ajaxIconById') }}',{ id: id })
                .done(function (data) {
                    if(data){

                        name = `<i class='${data.key}'></i> ${data.name}`;
                        newOption = new Option(name, data.id, true, true);
                        $(element).append(newOption).trigger('change');
                        console.log(element,newOption,$(element).append(newOption).trigger('change'));
                    }

                }).fail(function(data) {
                    //console.log(data);
                }).always(function() {
                    // console.log( "complete" );
                });*/


                name = `<i class='{{ $model->icon->key ?? 0 }}'></i> {{ $model->icon->name ?? '' }}`;
                newOption = new Option(name, {{ $model->icon->id ?? 0 }}, true, true);
                $(element).append(newOption).trigger('change');


            }


            $('#permission_child').select2({
                theme: 'bootstrap-5',
                language: 'es',
                placeholder: 'Seleccionar permiso',
                allowClear: true,
                dropdownParent: $('#newMenuModal')
            });

            //init components
            if($("#dropdown").is(':checked')){
                $('.component-hidden').show();
                $('.component-hidden-inverted').hide();
            }else{
                $('.component-hidden').hide();
                $('.component-hidden-inverted').show();
            }

            $('#alert_child').hide();

            $('#dropdown').click(function()
            {
                var effect=200;
                if($(this).is(':checked')){
                    $('.component-hidden').show(effect);
                    $('.component-hidden-inverted').hide(effect);

                }else{
                    $('.component-hidden').hide(effect);
                    $('.component-hidden-inverted').show(effect);
                }
            });
            //add to table view
            $('#btnAdd').click(function()
            {
                // $(".child").validate();
                if(!$("#name_child").val() || !$("#permission_child").val() || !$("#order_child").val()){
                    $('#alert_child').show(200);
                    return false;
                }
                if($("#title_btn_child").text() === "Modificar"){
                    $(_row).after(buildTableRow(_row.index()));
                    $(_row).remove();
                }else{
                    var tr = $('#table_children tbody tr:last');
                    var index = tr.index()+1;
                    $('#table_children tbody').append(buildTableRow(index));
                }

                $('#newMenuModal').modal('toggle');
                //clear
                clear();
            });
            //Modify Rows
            $(document).on("click",".btn-modify",function(e){
                //configuramos titulos y buttons
                $('#title_child').html('Modificar la');
                $('#title_btn_child').html('Modificar');
                if($("#btnAdd").hasClass( "btn-info" ))
                    $("#btnAdd").toggleClass('btn-info btn-warning');

                //comenzamos con el boton
                _row = $(this).parents("tr");
                var cols = _row.children("td");
                console.log(cols[2]);
                $("#name_child").val($(cols[0]).text());
                $("#permission_child").val($(cols[1]).attr("data-id"));
                $("#permission_child").change();
                $("#order_child").val($(cols[2]).text());
                // $("#published_child" ).attr( "checked", false );
                $('#published_child')[0].checked = false;
                if($(cols[3]).attr("data-id") == 'true' || $(cols[3]).attr("data-id") == '1'){
                    $('#published_child')[0].checked = true;
                }
                //open modal
                $('#newMenuModal').modal('toggle');

            });
            //Delete Rows
            $(document).on("click",".btn-confirm-custom",function(e){
                e.preventDefault();
                var btn=$(this);
                var message = btn.data('message');
                var href = btn.attr('href');
                bootbox.confirm({
                    message:message,
                    backdrop:true,
                    locale:'es',
                    callback:function callback(result){
                        console.log(message+' -> Response: '+result);
                        if(result){
                            console.log($(btn).closest("tr") );
                            $(btn).closest("tr").remove();
                        }
                    }
                });
            });
            //add Rows
            function buildTableRow(row){
                var name = $("#name_child").val();
                var permission = $("#permission_child").val();
                var permission_text = $("#permission_child option:selected").text();
                var published = $('#published_child').is(":checked")
                var order = $("#order_child").val();
                var option = ` <a href="#!" class="btn btn-icon btn-warning btn-modify" >
                                            <i class="mdi mdi-file-document-edit"></i>
                                        </a>
                                        <a data-message="¿Esta seguro de eliminar este registro?"
                                        href="#!" class="btn btn-icon btn-danger btn-confirm-custom">
                                            <i class="mdi mdi-trash-can"></i>
                                        </a>
                                        `;
                return `<tr>
                            <td>${name}</td>
                            <td data-id="${permission}">${permission_text}</td>
                            <td>${order}</td>
                            <td data-id="${published}">${published ? '<span class="badge bg-success">Publicado</span>' : '<span class="badge bg-secondary">No publicado</span>'}</td>
                            <td>${option}</td>

                            <td hidden>
                                <input type="hidden" name="row[${row}][name]" value="${name}"/>
                                <input type="hidden" name="row[${row}][permission_id]" value="${permission}"/>
                                <input type="hidden" name="row[${row}][order]" value="${order}"/>
                                <input type="hidden" name="row[${row}][permission_name]" value="${permission_text}"/>
                                <input type="hidden" name="row[${row}][published]" value="${published ? true : ''}"/>

                            </td>
                        </tr>`;
            }
             //clear
            function clear(){
                $(".child").val('').change();
                $('#alert_child').hide();
            }
            //modal open
            $('#openModal').click(function()
            {
                clear();
                $('#published_child')[0].checked = true;
                $('#title_child').html('Agregar una');
                $('#title_btn_child').html('Agregar');
                if($("#btnAdd").hasClass( "btn-warning" ))
                    $("#btnAdd").toggleClass('btn-warning btn-info');
            });
            //keypress action
            $('.child').on('keypress', function(e) {
                if(e.which == 13) {
                  $("#btnAdd").click();
                }
            });

        });


    </script>
@endpush
