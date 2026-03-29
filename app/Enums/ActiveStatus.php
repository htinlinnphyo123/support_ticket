<?php

namespace App\Enums;

enum ActiveStatus: string
{
    case Active = 'active';
    case Inactive = 'inactive';

    public function color(): string
    {
        return match($this) {
            self::Active => 'text-green-500',
            self::Inactive => 'text-red-500',
        };
    }

    public function label(): string
    {
        return match($this) {
            self::Active => 'Active',
            self::Inactive => 'Inactive',
        };
    }
}
