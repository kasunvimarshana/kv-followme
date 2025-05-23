<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class RecurringTypeEnum extends Enum
{
    const DEFAULT = 1;
    const GENERAL = 2;
    const TW_OWNER = 3;
    const TW_OWNER_HOD = 4;
}
