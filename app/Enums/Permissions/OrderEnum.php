<?php

namespace App\Enums\Permissions;

use App\Enums\Traits\Values;

enum OrderEnum: string
{
    use Values;
    case CREATE_ORDERS = 'create orders';
    case UPDATE_ORDERS = 'update orders';
    case DELETE_ORDERS = 'delete orders';
}
