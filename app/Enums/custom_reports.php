<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;


final class custom_reports extends Enum implements LocalizedEnum
{
    const Contracts = 1;
    const FinalAcknowledgment = 2;
    const Agreement = 3;
    const Reporte = 4;
}
