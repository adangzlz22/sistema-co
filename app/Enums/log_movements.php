<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class log_movements extends Enum implements LocalizedEnum
{
    const LogIn =   1;
    const LogOut =   2;
    const Edit = 3;
    const NewRegister = 4;
    const Inactive = 5;
    const Reactivate = 6;
    const Delete = 7;
    const ChangePassword = 8;
}
