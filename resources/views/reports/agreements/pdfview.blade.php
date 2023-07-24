<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>SICA | Reporte de Acuerdos</title>

<style type="text/css">
    html,body{
        padding: 20px;
        margin: 0;
    }

    html,body,* {
        font-family:  Arial, sans-serif;
        font-size: 12px;
        line-height: 1.4;
    }

    html,body{
        background: url({{ public_path('/images/patron.jpg') }});
        background-repeat: no-repeat;
        background-size: 96% auto;
        background-position: center bottom;
    }

    h6, .h6, h5, .h5, h4, .h4, h3, .h3, h2, .h2, h1, .h1 {
        margin-top: 0 !important;
        margin-bottom: 0.5rem !important;;
        font-weight: 600 !important;;
        line-height: 1.2 !important;;
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
</style>

</head>
<body>
    <h1>Lo que viene en HTML:</h1>


    {!! $htmlNote !!}


</body>
</html>
