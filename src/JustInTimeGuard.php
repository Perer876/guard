<?php

declare(strict_types=1);

namespace Guard;

use Override;
use ReflectionAttribute;
use ReflectionEnum;
use SplObjectStorage;

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
        /** @noinspection PhpUnhandledExceptionInspection */
        $enumReflection = new ReflectionEnum($permission::class);
        /** @noinspection PhpUnhandledExceptionInspection */
        $caseReflection = $enumReflection->getCase($permission->name);

        /** @var ReflectionAttribute<GrantToAttribute>[] $grantsToAttribute $grantsTo */
        $grantsToAttribute = [
            ...$enumReflection->getAttributes(GrantToAttribute::class, ReflectionAttribute::IS_INSTANCEOF),
            ...$caseReflection->getAttributes(GrantToAttribute::class, ReflectionAttribute::IS_INSTANCEOF),
        ];

        /** @var SplObjectStorage<Role, null> $grantedRoles */
        $grantedRoles = new SplObjectStorage();

        foreach ($grantsToAttribute as $grantToAttribute) {
            $grantTo = $grantToAttribute->newInstance();
            $grantedRoles->addAll($grantTo->roles);
        }

        return array_any(
            iterator_to_array($subject->getRoles()),
            static fn(Role $role): bool => $grantedRoles->contains($role),
        );
    }
}
