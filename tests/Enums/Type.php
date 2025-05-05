<?php

namespace Tests\Enums;

use PowerEnum\PowerEnum;

enum Type: int
{
    use PowerEnum;

    case Admin = 1;
    case User = 2;
    case Guest = 3;
}
