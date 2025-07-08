<?php

declare(strict_types=1);

namespace Guard;

use Override;
use ReflectionAttribute;
use ReflectionEnum;
use ReflectionException;
use SplObjectStorage;

/**
 * Just-in-time guard that checks if a subject has the permission based on the roles granted to that permission.
 * This guard uses reflection to dynamically check the roles associated with a permission at runtime.
 *
 * @internal
 */
final readonly class JustInTimeGuard implements Guard
{
    /**
     * @throws ReflectionException
     */
    #[Override]
    public function can(Subject $subject, Permission $permission): bool
    {
        $enumReflection = new ReflectionEnum($permission::class);
        $caseReflection = $enumReflection->getCase($permission->name);

        $grantedRoles = new SplObjectStorage();

        /** @var ReflectionAttribute<GrantToAttribute>[] $grantsToAttribute $grantsTo */
        $grantsToAttribute = [
            ...$enumReflection->getAttributes(GrantToAttribute::class, ReflectionAttribute::IS_INSTANCEOF),
            ...$caseReflection->getAttributes(GrantToAttribute::class, ReflectionAttribute::IS_INSTANCEOF),
        ];

        foreach ($grantsToAttribute as $grantToAttribute) {
            /** @var GrantToAttribute $grantTo */
            $grantTo = $grantToAttribute->newInstance();
            $grantedRoles->addAll($grantTo->roles);
        }

        return array_any($subject->getRoles(), static fn(Role $role): bool => $grantedRoles->contains($role));
    }
}
