<?php

declare(strict_types=1);

namespace Guard\Tests\Fixture;

use Guard\Role;

enum ExampleRole implements Role
{
    case Admin;
    case User;
    case Guest;
}
