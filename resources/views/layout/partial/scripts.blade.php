<!-- ================== BEGIN BASE JS ================== -->
<script src="{{ asset('js/vendor.min.js') }}"></script>
<script src="{{ asset('js/plugins/mask/mask.js') }}"></script>
<script src="{{ asset('js/app.min.js') }}"></script>

<!-- <script src="{{ asset('js/laravel-echo-setup.js') }}"></script> -->

{{-- añadidos --}}
<script src="{{ asset('js/plugins/moment/moment.js') }}"></script>
<script src="{{ asset('js/plugins/moment/locale/es-mx.js') }}"></script>
<script src="{{ asset('js/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.js') }}"></script>

<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
<script src="{{ asset('js/plugins/fullcalendar/dist/index.global.js') }}"></script>
<script src="https://unpkg.com/@popperjs/core@2"></script>
<script src="https://unpkg.com/tippy.js@6"></script>
<!-- ================== END BASE JS ================== -->

{{--<script src="https://{{ request()->getHost() }}/socket.io/socket.io.js"></script>--}}
{{--<script src="{{ url('/js/socket.io.js') }}" type="text/javascript"></script>--}}
{{--<script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.0.3/socket.io.js"></script>--}}

<script>
    $(document).ready(function () {
        initNotification();
        $("#myToast").toast({
            autohide: true,
            delay: 10000
        });
        MaskInputs();

        // NotificacionesSockets();
        // tablasResponsivas();
        customfilecount();
    });

    function NotificacionesSockets(){
        window.Echo.channel('menu.changes').listen('.menusChanges', (e) => {
            notify('Alerta cambios en el menú', e.message, 'Justo ahora', 1);
            reloadMeu();
        });
    }

    var MaskInputs = function () {
        $('.phone-mask').mask('000-000-0000');
        $(".just-int").mask("#");
        $('.just-decimal').mask("##0.00", {
            reverse: true
        });
        $('.hour-mask').mask("00:00", {placeholder: "__:__"});
        $('.date-mask').mask("00-00-0000", {placeholder: "__-__-____"});
    }

    //#region Menu
    function reloadMeu() {
        $.post('{{ route('menus.ajaxGetMenu') }}')
            .done(function (data) {
                $("#menu_template").html(data);
            }).fail(function (data) {
            console.log(data);
        }).always(function () {
            // console.log( "complete" );
        });
    }

    function loading_content(element){
        $('#notification-up-to-date').html('');
        element.html('<div class="loading-content"><div class="spinner-border text-primary"></div></div>');
    }

    var notPageNumber = 2;
    var notOnProcess = true;
    $('#show_notifications').click(function (){
        notPageNumber = 2;
        loading_content($("#notification_content"));
        $.get('{{ route('notifications.index') }}')
            .done(function (data) {
                $("#notification_content").html(data.html);
                $("#unread_notifications").html('0');
                if(data.has_more_pages)
                    notOnProcess=false;
            }).fail(function (data) {
            console.log(data);
        }).always(function () {
            // console.log( "complete" );
        });
    });


    $("#notification_content").scroll(function() {
        if($(this).scrollTop() + $(this).height() >= this.scrollHeight && !notOnProcess) {
            var element_more_data_loading = $("#more_data_loading");
            loading_content(element_more_data_loading);
            notOnProcess = true;
            $.get('{{ route('notifications.index') }}',{ page: notPageNumber})
                .done(function (data) {
                    notPageNumber += 1;
                    $("#notification_content").append(data.html);
                    if(!data.has_more_pages){
                        notOnProcess=true;
                        $("#notification_content").append('<div id="notification-up-to-date" class="notification-up-to-date"><i class="mdi mdi-flag"></i> Estas al día</div>');
                    }
                    else
                        notOnProcess = false;
                }).fail(function (data) {
                console.log(data);
            }).always(function () {
                element_more_data_loading.remove();
            });
        }

    });

    //#endregion


    function initNotification() {
        var hNotification = $("#notification").val();
        if(hNotification){
            var notification =  JSON.parse(hNotification);
            $.each(notification, function (key, value) {
                // alert(key + ": " + value.message);

                if(value.level !== 'success'){
                    notify(value.title, value.message, '', 2, value.level,0);
                }else{
                    notify(value.title, value.message, '', 2, value.level);
                }

            });
        }


        var hNotification_error = $("#notification_error").val();
        if(hNotification_error){
            var notification_error = JSON.parse($("#notification_error").val());

            $.each(notification_error, function (key, value) {
                // alert(key + ": " + value.message);
                notify(value, value, '', 2, 'danger',0);
            });
        }

    }

    function notify(title, message, time_ago, type_toast = 1, type_alert = 'success', delay = 5000) {

        let name_toast = '_' + Math.random().toString(36).substr(2, 9);
        let autohide = delay != 0 ? 'true' : 'false';

        switch (type_toast) {
            case 1:
                $('#toast_show').append(`
                        <div class="toast mt-2" id="${name_toast}" data-bs-autohide="${autohide}" data-bs-delay="${delay}">
                            <div class="toast-header">
                                <strong class="me-auto"><i class="bi-gift-fill"></i>${title}</strong>
                                <small>${time_ago}</small>
                                <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
                            </div>
                            <div class="toast-body">
                                ${message}
                            </div>
                        </div>`);
                $(`#${name_toast}`).toast("show");
                break;
            case 2:
                $('#toast_show').append(`
                    <div id="${name_toast}" class="toast align-items-center text-white bg-${type_alert} border-0 mt-2"
                    data-bs-autohide="${autohide}" data-bs-delay="${delay}" role="alert" aria-live="assertive" aria-atomic="true">
                        <div class="d-flex">
                            <div class="toast-body">
                                ${message}
                            </div>
                            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                        </div>
                    </div>`);
                $(`#${name_toast}`).toast("show");
                break;
            default:
                $('#toast_show').append(`
                        <div class="toast mt-2" id="${name_toast}" data-bs-autohide="${autohide}" data-bs-delay="${delay}">
                            <div class="toast-header">
                                <strong class="me-auto"><i class="bi-gift-fill"></i>${title}</strong>
                                <small>${time_ago}</small>
                                <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
                            </div>
                            <div class="toast-body">
                                ${message}
                            </div>
                        </div>`);
                $(`#${name_toast}`).toast("show");
                break;
        }

    }

    function resetForm(form_id) {

        try {
            $('#' + form_id).trigger("reset");
            $('#' + form_id+' .select2').trigger('change');
            $('#' + form_id+" .summernote").summernote("code", '');
        } catch (err) {
        }
    }

    //#region Loading
    function LoadigShow(effect = 0) {
        $('.loading').show(effect);
        $('.btn-save').prop('disabled', true);
    }

    function LoadingHide(effect = 0) {
        $('.loading').hide(effect);
        $('.btn-save').prop('disabled', false);

    }

    LoadingHide(200);

    //#endregion


    function CopyToClipboard(text) {
        var $temp = $("<input>");
        $("body").append($temp);
        $temp.val(text).select();
        document.execCommand("copy");
        $temp.remove();
        $("#myToastClipboard #toast-message").html('Copiado al portapapeles');
        $("#myToastClipboard").toast("show");
    }

    $(document).ajaxError(function myErrorHandler(event, xhr, ajaxOptions, thrownError) {
        if(xhr.status == 419 || xhr.status == 401) {
           location.reload();
        }
    });

    function goToScroll(id){
        $('#'+id)[0].scrollIntoView({ behavior: 'smooth', block: 'start', inline: 'start' })
    }

    // function tablasResponsivas(){
    //     $("table.responsiveDT").DataTable( {
    //         responsive: true,
    //         ordering: false,
    //         searching: false,
    //         paging: false,
    //         info: false
    //     } );
    // }

    function customfilecount(){
        var inputs = document.querySelectorAll( '.custom-files' );
        console.log(inputs);
        Array.prototype.forEach.call( inputs, function( input )
        {
            var label	 = input.parentElement.parentElement.nextElementSibling,
                labelVal = label.innerHTML;

            input.addEventListener( 'change', function( e )
            {
                var fileName = '';
                if( this.files && this.files.length > 1 )
                    fileName = ( this.getAttribute( 'data-multiple-caption' ) || '' ).replace( '{count}', this.files.length );
                else
                    fileName = e.target.value.split( '\\' ).pop();

                if( fileName )
                    label.querySelector( 'span' ).innerHTML = fileName;
                else
                    label.innerHTML = labelVal;
            });
        });
    }
    $('.btn-files').click(function () {
        LoadigShow();
        $.post("{{ route('files.detail') }}", { parent_id: $(this).data("parent_id"),mod_id: $(this).data("mod_id") })
            .done(function (data) {
                $('#files_detail_content').html(data);
            }).fail(function (data) {
            console.log(data);
        }).always(function () {
            LoadingHide(300);
        });
    });
</script>

@stack('scripts')
