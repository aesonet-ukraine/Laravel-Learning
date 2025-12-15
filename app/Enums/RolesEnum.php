<?php

namespace App\Enums;

use App\Enums\Traits\Values;

enum RolesEnum: string
{
    use Values;
    case ADMIN = 'admin';
    case USER = 'user';
    case MODERATOR = 'moderator';
    case SELLER = 'seller';

    public function labels(): string
    {
        return match ($this) {
            RolesEnum::ADMIN => 'admin',
            RolesEnum::USER => 'user',
            RolesEnum::MODERATOR => 'moderator',
            RolesEnum::SELLER => 'seller'
        };
    }
}
