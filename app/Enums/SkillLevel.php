<?php

namespace App\Enums;

enum SkillLevel: string
{
    case Core = 'core';
    case Proficient = 'proficient';
    case Familiar = 'familiar';

    public function label(): string
    {
        return match ($this) {
            self::Core => 'Tecnología principal',
            self::Proficient => 'Dominio profesional',
            self::Familiar => 'Con experiencia',
        };
    }
}
