<?php

namespace App\Enums\Permissions;

use App\Enums\Traits\Values;

enum AdminEnum: string
{
    use Values;
    case ADMIN = 'admin';

}
