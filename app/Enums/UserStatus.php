<?php

declare(strict_types=1);

namespace App\Enums;

enum UserStatus: string
{
    case Active = 'active';
    case Banned = 'banned';

    public function label(): string
    {
        return __('enums.user_status.'.$this->value);
    }
}
