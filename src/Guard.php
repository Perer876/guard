<?php

declare(strict_types=1);

namespace Guard;

interface Guard
{
    /**
     * Checks if the given subject has the specified permission.
     */
    public function can(Subject $subject, Permission $permission): bool;
}
