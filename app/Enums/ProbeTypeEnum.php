<?php

declare(strict_types=1);

namespace App\Enums;

enum ProbeTypeEnum: string
{
    case ENVIRONMENT = 'environment';
    case CAR = 'car';
    case BATTERY = 'battery';
}
