
{!! Form::select('agreementsSearch', [], '', ['class'=>'form-control select2Agreements','autocomplete'=>'off']) !!}



@push('scripts')

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.1.1/dist/select2-bootstrap-5-theme.min.css" />


    <script type="text/javascript">
        $(document).ready(function(){
            $('.select2Agreements').select2({
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
                placeholder: 'Buscar un acuerdo por número o descripción',
                //minimumInputLength: 1,
                ajax: {
                    url: '/acuerdos/ajax',
                    dataType: 'json',
                    method: 'POST',
                    delay: 250,
                    data: function (params) {
                        return {
                            term: params.term,
                            type: 'agreements'
                        }
                    },
                    processResults: function (data, page) {
                        return {
                            results: data
                        };
                    },
                }

            });


            $('.select2Agreements').on('select2:select', function (e) {
                var data = e.params.data;
                window.location = "/acuerdos/"+data.id+"/seguimiento/";
            });

            //Swal.fire('Hello world!');

        });


    </script>
@endpush
