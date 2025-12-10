<?php

namespace App\Enums\Permissions;

use App\Enums\Traits\Values;

enum AccountEnum: string
{
    use Values;
    case DELETE_ACCOUNT = 'delete account';
    case EDIT_ACCOUNT = 'edit account';
}
