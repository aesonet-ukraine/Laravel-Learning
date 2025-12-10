<?php

namespace App\Enums\Traits;

trait Values
{
    /**
     * Get all values of the enum cases.
     *
     * @return array <Integer, string>
     */
    public static function values(): array
    {
        return array_column(static::cases(), 'value');
    }
}
