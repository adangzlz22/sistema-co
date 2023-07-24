<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class priority extends Enum implements LocalizedEnum
{
    const High =   1;
    const Medium = 2;
    const Low = 3;
    const Na = 4;

    public static function getColor($enum_helper)
    {
        $color = '';
        switch ($enum_helper->value){
            case 1:
                $color = "#9B2F3E";
                break;

            case 2:
                $color = "#DC7F37";
                break;

            case 3:
                $color = "#2F9B8C";
                break;
            case 4:
                $color = "#676464";
                break;
        }

        return $color;
    }
}
