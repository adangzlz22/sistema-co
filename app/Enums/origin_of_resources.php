<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class origin_of_resources extends Enum implements LocalizedEnum
{
    const Estatal =   1;
    const Federal =   2;
    const Propio =   3;
}
