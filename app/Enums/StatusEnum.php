<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class StatusEnum extends Enum
{
    const DEFAULT = 1;
    const OPEN = 2;
    const CLOSE = 3;
    
    const VISIBLE_TRUE = true;
    const VISIBLE_FALSE = false;
}
