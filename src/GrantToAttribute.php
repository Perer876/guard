<?php

declare(strict_types=1);

namespace Guard;

use SplObjectStorage as ObjectStorage;

interface GrantToAttribute
{
    /**
     * @var ObjectStorage<Role, null>
     */
    public ObjectStorage $roles {
        get;
    }
}
