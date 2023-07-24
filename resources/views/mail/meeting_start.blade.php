<!doctype html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Notificación de Reunión</title>
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
                                        Notificación de Reunión
                                        </div><br>
                                        <p>Estimados compañeros, se les recuerda que se programó una reunión de {{$meeting->meeting_type->name}} para hoy a las {{$meeting->meeting_time}} horas, misma que se celebrará en {{ $meeting->modality->id==1?($meeting->place->name.", ".$meeting->place->address):($meeting->modality->id==2?'<a href="'.$meeting->link.'" target="_blank">enlace reunión virtual</a>':(($meeting->place->name.", ".$meeting->place->address).", ".('<a href="'.$meeting->link.'" target="_blank">enlace reunión virtual</a>')))}}.</p>
                                        <p>Favor de consultar en el sistema los temas que se revisarán de acuerdo con la temática del Gabinete, en caso de haber solicitado información específica sobre temas de interés del C. Gobernador, favor de capturarlos para ser considerados.</p>
                                        <p>No está de más recordar que la puntualidad es una virtud que debe ser respetada, por lo que se les pide estar presentes con al menos 10 minutos de anticipación.</p>  
                                        <p>Quedo a la orden para cualquier asunto.</p>
                                        <p>ATENTAMENTE</p>
                                        <p style="text-transform:capitalize">{{\App\Models\Entity::where('acronym','JOEE')->first()->holder??""}}</p>
                                        <p>JEFE DE LA OFICINA DEL EJECUTIVO ESTATAL</p>
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