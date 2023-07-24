<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class system_catalogues extends Enum implements LocalizedEnum
{
    const Users =   1;
    // const Agreements =   2;
    const Organisms = 3;
    const OrganismsTypes = 4;
    const AdministrativeUnits = 5;
    const Category = 6;
    const Icon = 7;
    const Menu = 8;
    const ContractType = 9;
    const JurisdictionContract = 10;
    const PaymentMethods = 11;
    const Contracts = 12;
    const ContractTrackig = 13;
    const AttendedContract = 14;
    const Jurisdictional = 15;
    const ExpedientType = 16;
    const TypeAgreement = 17;
    const AgrmntAgreement = 18;
    const Judgmennt = 19;
    const MeetingTypes = 20;
    const EntitiesTypes = 21;
    const Entities = 22;
    const Meetings = 23;
    const Status = 24;
    const Modalities = 25;
    const FileCategories = 26;
    const Subjects = 27;
    const Agreements =   28;
    const Replies =   29;
    const Actions =   30;
    const Files =   31;
    const Places =   32;
    const Indicators =   33;
}
