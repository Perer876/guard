<?php

declare(strict_types=1);

namespace Guard\Tests\Fixture;

use Guard\GrantTo;
use Guard\Permission;

#[GrantTo(ExampleRole::Admin)]
enum ExamplePermission implements Permission
{
    #[GrantTo(ExampleRole::User)]
    case ViewAny;

    #[GrantTo(ExampleRole::User, ExampleRole::Guest)]
    case View;

    #[GrantTo(ExampleRole::User)]
    #[GrantTo(ExampleRole::Guest)]
    case Read;

    #[GrantTo(ExampleRole::User, ExampleRole::User)]
    case Write;
    case Delete;
}
