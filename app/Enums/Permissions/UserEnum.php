<?php

namespace App\Enums\Permissions;

use App\Enums\Traits\Values;

enum UserEnum: string
{
    use Values;
    case CREATE_USER = 'create user';
    case DELETE_USER = 'delete user';
    case VIEW_USER = 'view user';
    case EDIT_USER = 'edit user';

}
