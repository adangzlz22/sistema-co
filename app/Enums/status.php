<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class status extends Enum implements LocalizedEnum
{
    const Saved = 1;
    const Registered = 2;
    const Received = 3;
    const Shifted = 4;
    const Revision = 5;
    const Recommendations = 6;
    const Signing = 10;
    const Attended = 7;
    const Cancelled = 8;
    const Finished = 9;

    public static function getColor($enum_helper)
    {
        $color = '';
        switch ($enum_helper->value) {
            case 1: //Guardado
                $color = "#6A6C6D";
                break;
            case 2: //Registrado
                $color = "#DC7F37";
                break;
            case 3: //Recibido
                $color = "#AF601A";
                break;
            case 4: //Turnado
                $color = "#ba5252"; //"#4682B4";
                break;
            case 5: //Revisión
                $color = "#FA8072";
                break;
            case 6: //Recomendaciones
                $color = "#4169E1";
                break;
            case 7://Atendido
                $color = "#2F9B8C";
                break;
            case 8: //Cancelado
                $color = "#A52A2A";
                break;
            case 9: //Cancelado
                $color = "#008080";
                break;
            case 10: //Signing
                $color = "#139eac";
                break;
            default:
                $color = "#777";
                break;
        }
        return $color;
    }
}
