<?php

declare(strict_types=1);

namespace Guard\Tests\Unit;

use Guard;
use Guard\Permission;
use Guard\Role;
use Guard\Tests\Fixture\ExamplePermission;
use PHPUnit\Framework\Attributes\CoversFunction;
use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\Attributes\Small;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClassesThatImplementInterface;
use PHPUnit\Framework\TestCase;

#[Small]
#[CoversFunction('Guard\granted_roles')]
#[UsesClassesThatImplementInterface(Guard\GrantToAttribute::class)]
final class GrantedRolesTest extends TestCase
{
    /**
     * @param list<Role> $expectedRoles
     */
    #[Test]
    #[DataProviderExternal(ExamplePermission::class, 'permissionAndRolesProvider')]
    public function itCanGetGrantedRolesFromPermission(Permission $permission, array $expectedRoles): void
    {
        $grantedRoles = Guard\granted_roles($permission);

        self::assertEqualsCanonicalizing($expectedRoles, iterator_to_array($grantedRoles));
    }
}
