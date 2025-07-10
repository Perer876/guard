<?php

declare(strict_types=1);

namespace Guard\Tests\Unit;

use Guard\GrantTo;
use Guard\Guard;
use Guard\JustInTimeGuard;
use Guard\Permission;
use Guard\Role;
use Guard\Subject;
use Guard\Tests\Fixture\ExamplePermission;
use Guard\Tests\Fixture\ExampleRole;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversClassesThatImplementInterface;
use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\Attributes\Small;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

#[Small]
#[CoversClass(JustInTimeGuard::class)]
#[CoversClass(GrantTo::class)]
#[CoversClassesThatImplementInterface(Guard::class)]
final class JustInTimeGuardTest extends TestCase
{
    /** @throws Exception */
    #[Test]
    #[DataProviderExternal(ExamplePermission::class, 'permissionAndRoleMappingProvider')]
    public function itCanCheckPermission(Permission $permission, Role $role, bool $expected): void
    {
        $guard = new JustInTimeGuard();
        $subject = $this->createMock(Subject::class);

        // Mock the subject's role
        $subject->method('getRoles')->willReturn([$role]);

        // Check if the subject can perform the permission
        self::assertSame($expected, $guard->can($subject, $permission));
    }

    /**
     * @param iterable<Role> $roles
     * @throws Exception
     */
    #[Test]
    #[DataProviderExternal(ExamplePermission::class, 'permissionAndRolesMappingProvider')]
    public function itCanCheckPermissionAgainstMultipleRoles(
        Permission $permission,
        iterable $roles,
        bool $expected,
    ): void {
        $guard = new JustInTimeGuard();
        $subject = $this->createMock(Subject::class);

        // Mock the subject's roles
        $subject->method('getRoles')->willReturn($roles);

        // Check if the subject can perform the permission
        self::assertSame($expected, $guard->can($subject, $permission));
    }

    /** @throws Exception */
    #[Test]
    public function itCanCheckPermissionAgainstNoRoles(): void
    {
        $guard = new JustInTimeGuard();
        $subject = $this->createMock(Subject::class);

        // Mock the subject to have no roles
        $subject->method('getRoles')->willReturn([]);

        // Check if the subject can perform any permission
        self::assertFalse($guard->can($subject, ExamplePermission::ViewAny));
        self::assertFalse($guard->can($subject, ExamplePermission::View));
        self::assertFalse($guard->can($subject, ExamplePermission::Create));
        self::assertFalse($guard->can($subject, ExamplePermission::Update));
        self::assertFalse($guard->can($subject, ExamplePermission::Delete));
    }

    /** @throws Exception */
    #[Test]
    public function itCanCheckPermissionAgainstDuplicatedRoles(): void
    {
        $guard = new JustInTimeGuard();
        $subject = $this->createMock(Subject::class);

        // Mock the subject to have duplicated roles
        $subject->method('getRoles')->willReturn([ExampleRole::User, ExampleRole::User]);

        self::assertTrue($guard->can($subject, ExamplePermission::View));
        self::assertFalse($guard->can($subject, ExamplePermission::Delete));
    }
}
