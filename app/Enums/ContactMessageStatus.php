<?php

namespace App\Enums;

enum ContactMessageStatus: string
{
    case Pending = 'pending';
    case Read = 'read';
    case Replied = 'replied';
    case Spam = 'spam';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Pendiente',
            self::Read => 'Leído',
            self::Replied => 'Respondido',
            self::Spam => 'Spam',
        };
    }
}
