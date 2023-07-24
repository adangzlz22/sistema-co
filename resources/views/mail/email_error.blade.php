<!doctype html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Notificación de error</title>
</head>

<body>
    <table width="100%" cellpadding="12" cellspacing="0" border="0">
        <tbody>
            <tr>
                <td>
                    <div style="overflow: hidden;">
                        <font size="-1">
                            <div id="m_7366766099684613151wrapper" style="color:#6a6a6a;width:520px;border:1px solid #ddd;margin:30px auto;font-family:Arial,Helvetica,sans-serif">
                                <div id="m_7366766099684613151header" style="padding:30px;border-bottom:1px solid #ddd">
                                    <div id="m_7366766099684613151logo" style="height:70px;text-align:center;margin-bottom:40px">
                                        <img src="https://www.sonora.gob.mx/images/template/logo-sonora.png" height="70">
                                    </div>
                                    <h1 style="font-size:24px;text-align:center;margin:0px;padding:0px;font-weight:700;color:#6a6a6a">
                                        <!-- Secretaría de Educación y Cultura -->
                                    </h1>
                                    <h2 style="font-size:16px;text-align:center;margin:0px;padding:0px;font-weight:700;color:#6a6a6a">
                                        <!-- Coordinación General de Registro, Certificación<br> y Servicios a Profesionistas. -->
                                    </h2>
                                    <h2 style="font-size:16px;text-align:center;margin:0px;padding:0px;font-weight:700;color:#6a6a6a">
                                        <!-- $var1$ -->
                                    </h2>
                                </div>
                                <div id="m_7366766099684613151body" style="padding:30px;margin-bottom:30px">
                                        <div id="texto" style="font-family:Arial,Helvetica,sans-serif;font-size:22px;margin:0px 0px;padding:0px;text-align:center;color:#999;font-weight:400">
                                        Notificación de error
                                        </div><br>
                                        <p>Error en el envío de notificación <strong>{{$tema}}</strong> de {{$meeting->folio}} {{$meeting->meeting_type->name}}</p>
                                        <p>Listado de correos a los que no fue posible notificar:</p>
                                        <ul>
                                            @foreach ($correos as $correo)
                                            <li>{{$correo->email}}</li>
                                            @endforeach
                                        </ul>
                                        <p>Verificar instituciones y usuarios que esten relacionados a los correos listados.</p>
                                        <p>Atentamente</p>
                                        {{ config('app.name') }}
                                        
                                    <hr style="background:#ccc;border:0px;border-top:1px solid #fff">
                                </div>
                                <div id="m_7366766099684613151footter" style="background-color:#eee;border-top:1px solid #ddd;color:#6a6a6a;padding:30px">
                                    <div style="text-align:center">
                                        <img src="https://www.sonora.gob.mx/images/template/logo-sonora.png" height="70">
                                    </div>
                                    <div style="font-size:12px;text-align:center;margin-top:15px">
                                    </div>
                                </div>
                            </div>
                        </font>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</body>
</html>