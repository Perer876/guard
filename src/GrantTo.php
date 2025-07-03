<?php

declare(strict_types=1);

namespace Guard;

use Attribute;
use SplObjectStorage as ObjectStorage;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_CLASS_CONSTANT | Attribute::IS_REPEATABLE)]
final readonly class GrantTo implements GrantToAttribute
{
    /** @var ObjectStorage<Role, null> */
    public ObjectStorage $roles;

    public function __construct(Role $role, Role ...$roles)
    {
        $this->roles = new ObjectStorage();

        foreach ([$role, ...$roles] as $oneRole) {
            $this->roles->attach($oneRole);
        }
    }
}
