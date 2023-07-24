<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class government_level extends Enum implements LocalizedEnum
{
    const State =   0;
    const Federal =   1;
    const Municipal = 2;
}
