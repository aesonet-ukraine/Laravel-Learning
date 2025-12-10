<?php

namespace App\Enums\Permissions;

use App\Enums\Traits\Values;

enum ProductEnum: string
{
    use Values;
    case CREATE_PRODUCTS = 'create product';
    case UPDATE_PRODUCTS = 'update product';
    case DELETE_PRODUCTS = 'delete product';

}
