<?php

declare(strict_types=1);

namespace Guard\Tests\Fixture;

use Generator;
use Guard\GrantTo;
use Guard\Permission;
use Guard\Role;

#[GrantTo(ExampleRole::Admin)]
enum ExamplePermission implements Permission
{
    #[GrantTo(ExampleRole::User)]
    case ViewAny;

    #[GrantTo(ExampleRole::User, ExampleRole::Guest)]
    case View;

    #[GrantTo(ExampleRole::User)]
    #[GrantTo(ExampleRole::Guest)]
    case Create;

    #[GrantTo(ExampleRole::User, ExampleRole::User)]
    case Update;
    case Delete;

    /**
     * @return Generator<int, array{0: Permission, 1: list<Role>}, mixed, void>
     */
    public static function permissionAndRolesProvider(): Generator
    {
        yield [self::ViewAny, [ExampleRole::Admin, ExampleRole::User]];
        yield [self::View, [ExampleRole::Admin, ExampleRole::User, ExampleRole::Guest]];
        yield [self::Create, [ExampleRole::Admin, ExampleRole::User, ExampleRole::Guest]];
        yield [self::Update, [ExampleRole::Admin, ExampleRole::User]];
        yield [self::Delete, [ExampleRole::Admin]];
    }

    /**
     * @return Generator<int, array{0: Permission, 1: Role, 2: boolean}, mixed, void>
     */
    public static function permissionAndRoleMappingProvider(): Generator
    {
        yield [self::ViewAny, ExampleRole::Admin, true];
        yield [self::ViewAny, ExampleRole::User, true];
        yield [self::ViewAny, ExampleRole::Guest, false];
        yield [self::View, ExampleRole::Admin, true];
        yield [self::View, ExampleRole::User, true];
        yield [self::View, ExampleRole::Guest, true];
        yield [self::Create, ExampleRole::Admin, true];
        yield [self::Create, ExampleRole::User, true];
        yield [self::Create, ExampleRole::Guest, true];
        yield [self::Update, ExampleRole::Admin, true];
        yield [self::Update, ExampleRole::User, true];
        yield [self::Update, ExampleRole::Guest, false];
        yield [self::Delete, ExampleRole::Admin, true];
        yield [self::Delete, ExampleRole::User, false];
        yield [self::Delete, ExampleRole::Guest, false];
    }

    /**
     * @return Generator<string, array{0: Permission, 1: iterable<Role>, 2: boolean}, mixed, void>
     */
    public static function permissionAndRolesMappingProvider(): Generator
    {
        yield 'All granted' => [
            self::ViewAny,
            [ExampleRole::Admin, ExampleRole::User],
            true,
        ];
        yield 'Just one granted' => [
            self::ViewAny,
            [ExampleRole::Admin, ExampleRole::Guest],
            true,
        ];
        yield 'None granted' => [
            self::Delete,
            [ExampleRole::Guest, ExampleRole::User],
            false,
        ];
    }
}
