<?php

declare(strict_types=1);

namespace Guard;

use Override;

/**
 * Just-in-time guard that checks if a subject has the permission based on the roles granted to that permission.
 * This guard uses reflection to dynamically check the roles associated with a permission at runtime.
 *
 * @internal
 */
final readonly class JustInTimeGuard implements Guard
{
    #[Override]
    public function can(Subject $subject, Permission $permission): bool
    {
        $grantedRoles = granted_roles($permission);

        return array_any(
            iterator_to_array($subject->getRoles()),
            static fn(Role $role): bool => $grantedRoles->contains($role),
        );
    }
}
