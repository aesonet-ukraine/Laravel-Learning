<?php

namespace App\Enums\Permissions;

use App\Enums\Traits\Values;

enum CategoryEnum: string
{
    use Values;
    case CREATE_CATEGORY = 'create category';
    case UPDATE_CATEGORY = 'update category';
    case DELETE_CATEGORY = 'delete category';

}
