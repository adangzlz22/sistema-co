<?php

namespace App\Helpers;

use App\Models\Log;
use Carbon\Carbon;
use File;
use Illuminate\Support\Facades\Event;

class HelperApp
{
    public static $path_save_user_profile = "/user_profile/";
    public static $roleDependenciaEntidad = "Enlace Dependencia/Entidad Consultivo";
    public static $roleRevisorConsultivo = "Revisor Consultivo";
    public static $roleAdministradorConsultivo = "Administrador Consultivo";
    public static $roleAdministradorConsejeria = "Administrador Consejería";
    public static $roleSuperUsuario = "Super Usuario";
    public static $roleAdministradorJuicios = "Administrador Juicios";
    public static $roleDependenciaEntidadJuicios = "Enlace Dependencia/Entidad Consultivo";
    public static $roleRevisorConsultivoJuicios = "Revisor Juicios";
    public static $roleEntidad = "Institucion";

    public static function save_file($file, $path, $filename = 'file')
    {
        if(!File::isDirectory($path))
            File::makeDirectory($path,0777, true);

        $filename = $filename.'-' . time() . '.' . $file->getClientOriginalExtension();
        try{
            $path = $file->storeAs($path, $filename);

        }catch (\Exception $exception)
        {
            dd($exception);
        }
        return $path;
    }

    public static function save_log($register_id, $movement, $catalogue, $old_info, $new_info)
    {
        $log = new Log();
        $log->user_id = auth()->user()->id ?? 1 ;
        $log->date = HelperApp::get_datetime();
        $log->movement = $movement;
        $log->old_info = $old_info;
        $log->new_info = $new_info;
        $log->catalogue = $catalogue;
        $log->register_id = $register_id;

        return Log::create($log);
    }

    public static function get_datetime()
    {
        $datetime_now = Carbon::now()->setTimezone('GMT-7');
        return $datetime_now;
    }

    public static function ActiveMenuControllers($controllers)
    {
        return in_array(request()->segment(1), $controllers) ? 'active' : '';
    }


    public static function get_abecedario($position)
    {
        $abecedario = "ABCDEFGHIJKLMNOPQRSTUV";
        return $abecedario[$position];
    }

    public static function ColorStatus($status){

    }

    //SOCKET
    public static function EventMenuChage(){
       event(new \App\Events\MenuMessage);
    }

    public static function UpperCase($str){

        mb_internal_encoding('UTF-8');
        if(!mb_check_encoding($str, 'UTF-8')
            OR !($str === mb_convert_encoding(mb_convert_encoding($str, 'UTF-32', 'UTF-8' ), 'UTF-8', 'UTF-32'))) {

            $str = mb_convert_encoding($str, 'UTF-8');
        }
        return mb_convert_case($str, MB_CASE_UPPER, "UTF-8");
    }

    public static function convert_request_to_uppercase($request){
        foreach ($request->except(['_token', '_method']) as $key => $value) {
            $request[$key] = HelperApp::UpperCase($value);
        }
    }

    public static function convertir_numero_a_letra($num, $fem = false, $dec = true) {
        set_time_limit(900000);
        $matuni[2]  = "DOS";
        $matuni[3]  = "TRES";
        $matuni[4]  = "CUATRO";
        $matuni[5]  = "CINCO";
        $matuni[6]  = "SEIS";
        $matuni[7]  = "SIETE";
        $matuni[8]  = "OCHO";
        $matuni[9]  = "NUEVE";
        $matuni[10] = "DIEZ";
        $matuni[11] = "ONCE";
        $matuni[12] = "DOCE";
        $matuni[13] = "TRECE";
        $matuni[14] = "CATORCE";
        $matuni[15] = "QUINCE";
        $matuni[16] = "DIECISEIS";
        $matuni[17] = "DIECISIETE";
        $matuni[18] = "DIECIOCHO";
        $matuni[19] = "DIECINUEVE";
        $matuni[20] = "VEINTE";
        $matunisub[2] = "DOS";
        $matunisub[3] = "TRES";
        $matunisub[4] = "CUATRO";
        $matunisub[5] = "QUIN";
        $matunisub[6] = "SEIS";
        $matunisub[7] = "SETE";
        $matunisub[8] = "OCHO";
        $matunisub[9] = "NOVE";

        $matdec[2] = "VEINT";
        $matdec[3] = "TREINTA";
        $matdec[4] = "CUARENTA";
        $matdec[5] = "CINCUENTA";
        $matdec[6] = "SESENTA";
        $matdec[7] = "SETENTA";
        $matdec[8] = "OCHENTA";
        $matdec[9] = "NOVENTA";
        $matsub[3]  = 'MILL';
        $matsub[5]  = 'BILL';
        $matsub[7]  = 'MILL';
        $matsub[9]  = 'TRILL';
        $matsub[11] = 'MILL';
        $matsub[13] = 'BILL';
        $matsub[15] = 'MILL';
        $matmil[4]  = 'MILLONES';
        $matmil[6]  = 'BILLONES';
        $matmil[7]  = 'DE BILLONES';
        $matmil[8]  = 'MILLONES DE BILLONES';
        $matmil[10] = 'TRILLONES';
        $matmil[11] = 'DE TRILLONES';
        $matmil[12] = 'MILLONES DE TRILLONES';
        $matmil[13] = 'DE TRILLONES';
        $matmil[14] = 'BILLONES DE TRILLONES';
        $matmil[15] = 'DE BILLONES DE TRILLONES';
        $matmil[16] = 'MILLONES DE BILLONES DE TRILLONES';
        $num;

        //Zi hack
        $float=explode('.',$num);
        $num=$float[0];

        $num = trim((string)@$num);
        if ($num[0] == '-') {
            $neg = 'menos ';
            $num = substr($num, 1);
        }else
            $neg = '';
        while ($num[0] == '0') $num = substr($num, 1);
        if ($num[0] < '1' or $num[0] > 9) $num = '0' . $num;
        $zeros = true;
        $punt = false;
        $ent = '';
        $fra = '';
        for ($c = 0; $c < strlen($num); $c++) {
            $n = $num[$c];
            if (! (strpos(".,'''", $n) === false)) {
                if ($punt) break;
                else{
                    $punt = true;
                    continue;
                }

            }elseif (! (strpos('0123456789', $n) === false)) {
                if ($punt) {
                    if ($n != '0') $zeros = false;
                    $fra .= $n;
                }else

                    $ent .= $n;
            }else

                break;

        }
        $ent = '     ' . $ent;
        if ($dec and $fra and ! $zeros) {
            $fin = ' coma';
            for ($n = 0; $n < strlen($fra); $n++) {
                if (($s = $fra[$n]) == '0')
                    $fin .= ' CERO';
                elseif ($s == '1')
                    $fin .= $fem ? ' UNA' : ' UN';
                else
                    $fin .= ' ' . $matuni[$s];
            }
        }else
            $fin = '';
        if ((int)$ent === 0) return 'CERO ' . $fin;
        $tex = '';
        $sub = 0;
        $mils = 0;
        $neutro = false;
        while ( ($num = substr($ent, -3)) != '   ') {
            $ent = substr($ent, 0, -3);
            if (++$sub < 3 and $fem) {
                $matuni[1] = 'UNA';
                $subcent = 'AS';
            }else{
                $matuni[1] = $neutro ? 'UN' : 'UNO';
                $subcent = 'OS';
            }
            $t = '';
            $n2 = substr($num, 1);
            if ($n2 == '00') {
            }elseif ($n2 < 21)
                $t = ' ' . $matuni[(int)$n2];
            elseif ($n2 < 30) {
                $n3 = $num[2];
                if ($n3 != 0) $t = 'I' . $matuni[$n3];
                $n2 = $num[1];
                $t = ' ' . $matdec[$n2] . $t;
            }else{
                $n3 = $num[2];
                if ($n3 != 0) $t = ' Y ' . $matuni[$n3];
                $n2 = $num[1];
                $t = ' ' . $matdec[$n2] . $t;
            }
            $n = $num[0];
            if ($n == 1) {
                $t = ' CIENTO' . $t;
            }elseif ($n == 5){
                $t = ' ' . $matunisub[$n] . 'IENT' . $subcent . $t;
            }elseif ($n != 0){
                $t = ' ' . $matunisub[$n] . 'CIENT' . $subcent . $t;
            }
            if ($sub == 1) {
            }elseif (! isset($matsub[$sub])) {
                if ($num == 1) {
                    $t = ' MIL';
                }elseif ($num > 1){
                    $t .= ' MIL';
                }
            }elseif ($num == 1) {
                $t .= ' ' . $matsub[$sub] . '?n';
            }elseif ($num > 1){
                $t .= ' ' . $matsub[$sub] . 'ONES';
            }
            if ($num == '000') $mils ++;
            elseif ($mils != 0) {
                if (isset($matmil[$sub])) $t .= ' ' . $matmil[$sub];
                $mils = 0;
            }
            $neutro = true;
            $tex = $t . $tex;
        }
        $tex = $neg . substr($tex, 1) . $fin;
        //Zi hack --> return ucfirst($tex);
        //$end_num=ucfirst($tex).' PESOS 00/100 M. N.';
        return $tex;
    }

    // Ejem: 09-01-2023 -> 2023-01-09
    public static function formatDate($vdate)
    {
        $fch = explode("-", $vdate);
        $tdate = $fch[2] . "-" . $fch[1] . "-" . $fch[0];

        return $tdate;
    }	
}
