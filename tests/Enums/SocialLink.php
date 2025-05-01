<?php

namespace Tests\Enums;

use PowerEnum\PowerEnum;

enum SocialLink: string
{
    use PowerEnum;

    case Blog = 'blog';
    case Contact = 'contact';
    case Website = 'website';
}
