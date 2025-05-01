<?php

namespace Tests\Enums;

use PowerEnum\PowerEnum;

enum Status: string
{
    use PowerEnum;

    case Published = 'published';
    case Hidden = 'hidden';
    case Draft = 'draft';

    public function getLabel(): string
    {
        return match ($this) {
            self::Published => 'Is Published',
            self::Hidden => 'Is Hidden',
            self::Draft => 'Is Draft',
        };
    }
}
