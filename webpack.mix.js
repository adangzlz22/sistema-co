const mix = require('laravel-mix');
//require('laravel-mix-dload');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */


// core cssc

//mix.disableSuccessNotifications();


//Archivo App
mix.sass('resources/scss/styles.scss', 'public/css/app.min.css');

if(true){

//Archivo Vendor
    mix.combine([
        'node_modules/@fortawesome/fontawesome-free/css/all.min.css',
        'node_modules/jquery-ui-dist/jquery-ui.min.css',
        'node_modules/animate.css/animate.min.css',
        'node_modules/pace-js/themes/black/pace-theme-flash.css',
        'node_modules/select2/dist/css/select2.min.css',
        'node_modules/sweetalert2/dist/sweetalert2.min.css',
        'node_modules/select2-bootstrap-5-theme/dist/select2-bootstrap-5-theme.min.css'
    ], 'public/css/vendor.min.css');



    mix.copy('resources/scss/images/', 'public/css/images/');

    mix.copy('vendor/proengsoft/laravel-jsvalidation/resources/views', 'resources/views/vendor/jsvalidation')
        .copy('vendor/proengsoft/laravel-jsvalidation/public', 'public/vendor/jsvalidation');





// core js
    mix.combine(['resources/js/app.js'], 'public/js/app.min.js');
    mix.js('resources/js/laravel-echo-setup.js', 'public/js');


    mix.combine([
        'node_modules/pace-js/pace.min.js',
        'node_modules/jquery/dist/jquery.min.js',
        'node_modules/jquery-ui-dist/jquery-ui.min.js',
        'node_modules/bootstrap/dist/js/bootstrap.bundle.min.js',
        'node_modules/jquery-slimscroll/jquery.slimscroll.min.js',
        'node_modules/bootbox/dist/bootbox.all.min.js',
        'node_modules/js-cookie/src/js.cookie.js',
        'node_modules/select2/dist/js/select2.full.min.js',
        'node_modules/select2/dist/js/i18n/es.js',
        'node_modules/sweetalert2/dist/sweetalert2.all.min.js'
    ], 'public/js/vendor.min.js');


// plugins
    mix.copy('node_modules/bootstrap/', 'public/js/plugins/bootstrap/');
    mix.copy('node_modules/pace-js/', 'public/js/plugins/pace-js/');
    mix.copy('node_modules/jquery/', 'public/js/plugins/jquery/');
    mix.copy('node_modules/jquery-ui-dist/', 'public/js/plugins/jquery-ui-dist/');
    mix.copy('node_modules/jquery-slimscroll/', 'public/js/plugins/jquery-slimscroll/');
    mix.copy('node_modules/js-cookie/', 'public/js/plugins/js-cookie/');
    mix.copy('node_modules/@fortawesome/', 'public/js/plugins/@fortawesome/');
    mix.copy('node_modules/@fortawesome/fontawesome-free/webfonts/', 'public/webfonts/');
//mix.copy('node_modules/@fullcalendar/', 'public/js/plugins/@fullcalendar/');
//mix.copy('node_modules/apexcharts/', 'public/js/plugins/apexcharts/');
//mix.copy('node_modules/blueimp-file-upload/', 'public/js/plugins/blueimp-file-upload/');
//mix.copy('node_modules/blueimp-tmpl/', 'public/js/plugins/blueimp-tmpl/');
//mix.copy('node_modules/blueimp-gallery/', 'public/js/plugins/blueimp-gallery/');
//mix.copy('node_modules/blueimp-canvas-to-blob/', 'public/js/plugins/blueimp-canvas-to-blob/');
//mix.copy('node_modules/blueimp-load-image/', 'public/js/plugins/blueimp-load-image/');
    mix.copy('node_modules/bootstrap-datepicker/', 'public/js/plugins/bootstrap-datepicker/');
    mix.copy('node_modules/bootstrap-daterangepicker/', 'public/js/plugins/bootstrap-daterangepicker/');
//mix.copy('node_modules/bootstrap-slider/', 'public/js/plugins/bootstrap-slider/');
    mix.copy('node_modules/bootstrap-timepicker/', 'public/js/plugins/bootstrap-timepicker/');
    //mix.copy('node_modules/bootstrap-3-typeahead/', 'public/js/plugins/bootstrap-3-typeahead/');
//mix.copy('node_modules/bootstrap-table/', 'public/js/plugins/bootstrap-table/');
    mix.copy('node_modules/chart.js/', 'public/js/plugins/chart.js/');
// mix.copy('node_modules/datatables.net/', 'public/js/plugins/datatables.net/');
// mix.copy('node_modules/datatables.net-bs4/', 'public/js/plugins/datatables.net-bs4/');
// mix.copy('node_modules/datatables.net-autofill/', 'public/js/plugins/datatables.net-autofill/');
// mix.copy('node_modules/datatables.net-autofill-bs4/', 'public/js/plugins/datatables.net-autofill-bs4/');
// mix.copy('node_modules/datatables.net-buttons/', 'public/js/plugins/datatables.net-buttons/');
// mix.copy('node_modules/datatables.net-buttons-bs4/', 'public/js/plugins/datatables.net-buttons-bs4/');
// mix.copy('node_modules/datatables.net-colreorder/', 'public/js/plugins/datatables.net-colreorder/');
// mix.copy('node_modules/datatables.net-colreorder-bs4/', 'public/js/plugins/datatables.net-colreorder-bs4/');
// mix.copy('node_modules/datatables.net-fixedcolumns/', 'public/js/plugins/datatables.net-fixedcolumns/');
// mix.copy('node_modules/datatables.net-fixedcolumns-bs4/', 'public/js/plugins/datatables.net-fixedcolumns-bs4/');
// mix.copy('node_modules/datatables.net-fixedheader/', 'public/js/plugins/datatables.net-fixedheader/');
// mix.copy('node_modules/datatables.net-fixedheader-bs4/', 'public/js/plugins/datatables.net-fixedheader-bs4/');
// mix.copy('node_modules/datatables.net-keytable/', 'public/js/plugins/datatables.net-keytable/');
// mix.copy('node_modules/datatables.net-keytable-bs4/', 'public/js/plugins/datatables.net-keytable-bs4/');
// mix.copy('node_modules/datatables.net-responsive/', 'public/js/plugins/datatables.net-responsive/');
// mix.copy('node_modules/datatables.net-responsive-bs4/', 'public/js/plugins/datatables.net-responsive-bs4/');
// mix.copy('node_modules/datatables.net-rowgroup/', 'public/js/plugins/datatables.net-rowgroup/');
// mix.copy('node_modules/datatables.net-rowgroup-bs4/', 'public/js/plugins/datatables.net-rowgroup-bs4/');
// mix.copy('node_modules/datatables.net-rowreorder-bs4/', 'public/js/plugins/datatables.net-rowreorder-bs4/');
// mix.copy('node_modules/datatables.net-scroller/', 'public/js/plugins/datatables.net-scroller/');
// mix.copy('node_modules/datatables.net-scroller-bs4/', 'public/js/plugins/datatables.net-scroller-bs4/');
// mix.copy('node_modules/datatables.net-select/', 'public/js/plugins/datatables.net-select/');
// mix.copy('node_modules/datatables.net-select-bs4/', 'public/js/plugins/datatables.net-select-bs4/');
// mix.copy('node_modules/jvectormap-next/', 'public/js/plugins/jvectormap-next/');
// mix.copy('node_modules/jquery-migrate/dist/', 'public/js/plugins/jquery-migrate/dist/');
// mix.copy('node_modules/jquery.maskedinput/', 'public/js/plugins/jquery.maskedinput/');
// mix.copy('node_modules/kbw-countdown/', 'public/js/plugins/kbw-countdown/');
// mix.copy('node_modules/masonry-layout/dist/', 'public/js/plugins/masonry-layout/dist/');
    mix.copy('node_modules/moment/', 'public/js/plugins/moment/');
// mix.copy('node_modules/photoswipe/', 'public/js/plugins/photoswipe/');
// mix.copy('node_modules/select-picker/', 'public/js/plugins/select-picker/');
// mix.copy('node_modules/spectrum-colorpicker2/dist/', 'public/js/plugins/spectrum-colorpicker2/dist/');
// mix.copy('node_modules/summernote/', 'public/js/plugins/summernote/');
// mix.copy('node_modules/tag-it/', 'public/js/plugins/tag-it/');
    mix.copy('node_modules/jquery-mask-plugin', 'public/js/plugins/jquery-mask-plugin');
    mix.copy('node_modules/lightbox2', 'public/js/plugins/lightbox2');


//mix.copy('vendor/proengsoft/laravel-jsvalidation/resources/views', 'resources/views/vendor/jsvalidation');
//mix.copy('vendor/proengsoft/laravel-jsvalidation/public', 'public/js/vendor/jsvalidation');




    /*mix.download({
        enabled: true,
        urls: [{
            'url': 'http://cdnjs.cloudflare.com/ajax/libs/highlight.js/10.4.0/styles/default.min.css',
            'dest': 'public/js/plugins/highlight.js/'
        },{
            'url': 'http://cdnjs.cloudflare.com/ajax/libs/highlight.js/10.4.0/highlight.min.js',
            'dest': 'public/js/plugins/highlight.js/'
        },{
            'url': 'https://jvectormap.com/js/jquery-jvectormap-world-mill.js',
            'dest': 'public/js/plugins/jvectormap-next/'
        }]
    });
    */
}

