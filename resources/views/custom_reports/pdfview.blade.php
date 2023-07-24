<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>{{getenv('APP_NAME')}} | Reporte </title>

<style type="text/css">
    .custom-report{
        margin-left: 80px;
        margin-right: 80px;
        margin-bottom: 200px;
    }

    html,body{
        margin: 0;
        padding: 0;
        margin-top: 30px;
    }
    .rayita-arriba
    {
        top:-30px;
        width: 100%;
        border-top: 15px solid #B94645;
        position: fixed;

    }
/*
    html,body,* {
        font-family:  Arial, sans-serif;
        font-size: 12px;
        line-height: 1.4;
    }*/

    h6, .h6, h5, .h5, h4, .h4, h3, .h3, h2, .h2, h1, .h1 {
        margin-top: 0 !important;
        margin-bottom: 0.5rem !important;;
        font-weight: 600 !important;;
        line-height: 1.2 !important;;
    }

    html,body{
        background: url({{ public_path('/images/patron.jpg') }});
        background-repeat: no-repeat;
        background-size: 96% auto;
        background-position: center bottom;
    }


    table{
        font-size: x-small;
    }
    tfoot tr td{
        font-weight: bold;
        font-size: x-small;
    }


    #TableHeader{
        border-collapse: collapse;
        border-spacing: initial;
        background-color: #f2f2f2;
    }
    #TableHeader thead th, #TableHeader tbody td{
        font-family:  Arial, sans-serif;
        padding: 4px;
        font-size: 12px;
        text-align: center;
        vertical-align: middle;
        border: 1px solid #fff;
    }

    #TableHeader thead th h3, #TableHeader tbody td h3{
        font-weight: 700;
        font-size: 14px;
        margin-bottom: 4px;
    }



    #TableTdCenter{
        border-collapse: collapse;
        border-spacing: 0;
    }


    #TableTdCenter thead th
    {
        text-align: center;
        vertical-align: middle;
        padding: 4px;
        background: #e6e6e6;
        font-weight: 600;
        border: 1px solid #333;
        font-family: Arial, Helvetica, sans-serif !important;
    }

    #TableTdCenter tbody td
    {
        text-align: center;
        vertical-align: middle;
        padding: 4px;
        border: 1px solid #333;
        font-family: Arial, Helvetica, sans-serif !important;
    }
    .gray {
        background-color: lightgray
    }

    header{
        margin-bottom: 12px;
        padding: 12px 0;
        border-bottom: 1px solid #ccc;
        border-top: 12px solid #b94645;
    }

    footer {
        position: fixed;
        left: 20px;
        bottom: 20px;
        right: 20px;
        height: 40px;
        border-top: 1px solid #ccc;
        font-size: 9px;
    }
    footer .page:after {
      content: counter(page);
    }
    footer table {
      width: 100%;
    }
    footer p {
      text-align: right;
    }
    footer .izq {
      text-align: left;
    }


    .footer-text{
        width: 100%;
        bottom: 20px;
        position: absolute;
        text-align: center;
        font-size: 11px;
        font-weight: bolder;
        margin-left: 80px;
        margin-right: 80px;
    }
    .qr-section{
        width: 100%;
        position:absolute;
        bottom: 30px;
        text-align: center;
        font-size: 11px;
        font-weight: bolder;
        height: 200px;
    }
    .div-line-bottom{
        border-bottom: 3px solid #B94645;
        width: 100% !important;
    }

    footer {
        position: fixed;
        bottom: 0px;
        left: 0px;
        right: 0px;
        height: 50px;
        border:0;

        text-align: center;
        font-size: 11px;
        font-weight: bolder;
        margin-left: 80px;
        margin-right: 80px;
    }
</style>

</head>
<body>

    <div class="rayita-arriba"></div>


    <div class="custom-report" >
        {!! $model->html_report !!}
    </div>

        <div class="qr-section">
            <div style="text-align: left!important; margin: 10px;">
                <div style="text-align: center!important; margin-bottom: 10px;display:inline;float:left; width:20%;">
                    <img src="data:image/svg+xml;base64, {{ base64_encode(QrCode::format('svg')->size(100)->generate($qr_code)) }} ">
                </div>
                <div style="display:inline; float:left; width: 80%;">
                    <div class="div-line-bottom">
                        Cadena original:
                    </div>

                    <div style="word-break:break-all; word-wrap:break-word;color: #92a2a8;">
                        {{ $qr_code }}
                    </div>

                    <div class="div-line-bottom">
                        Sello digital:
                    </div>
                    <div style="word-break:break-all; word-wrap:break-word;color: #92a2a8;">
                        {{ $code_sha512 }}
                    </div>
                </div>
            </div>
        </div>


<!--<script type="text/php">

        $pdf->page_script('
            if($PAGE_NUM == 1){
                $pdf->text(0,0,'pablo',null,10,array(0,0,0))
            }
        ')

</script>-->
</body>
</html>
