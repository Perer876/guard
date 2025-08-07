<?php

declare(strict_types=1);

namespace Guard;

use ReflectionAttribute;
use ReflectionEnum;
use SplObjectStorage as ObjectStorage;

/**
 * Returns the roles granted to a specific permission.
 *
 * @return ObjectStorage<Role, null>
 */
function granted_roles(Permission $permission): ObjectStorage
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

    /** @var ObjectStorage<Role, null> $grantedRoles */
    $grantedRoles = new ObjectStorage();

    foreach ($grantsToAttribute as $grantToAttribute) {
        $grantTo = $grantToAttribute->newInstance();
        $grantedRoles->addAll($grantTo->roles);
    }

    return $grantedRoles;
}
