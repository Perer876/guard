<?php

declare(strict_types=1);

namespace Guard;

/**
 * @phpstan-require-implements Subject
 * @psalm-require-implements Subject
 */
trait AsSubject
{
    final public function hasPermission(Permission $permission): bool
    {
        // TODO: Replace with a proper guard implementation and resolve from container.
        return new JustInTimeGuard()->can($this, $permission);
    }
}
