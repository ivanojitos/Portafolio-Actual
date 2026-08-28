<?php

namespace App\Enums;

enum SkillCategory: string
{
    case Backend = 'backend';
    case Frontend = 'frontend';
    case Database = 'database';
    case Architecture = 'architecture';
    case DevOps = 'devops';
    case Tools = 'tools';

    public function label(): string
    {
        return match ($this) {
            self::Backend => 'Backend',
            self::Frontend => 'Frontend',
            self::Database => 'Bases de datos',
            self::Architecture => 'Arquitectura y calidad',
            self::DevOps => 'DevOps',
            self::Tools => 'Metodologías y herramientas',
        };
    }
}
