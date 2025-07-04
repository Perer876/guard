<?php

declare(strict_types=1);

namespace Guard;

use Override;

/**
 * @internal
 */
final readonly class JustInTimeGuard implements Guard
{
    #[Override]
    public function can(Subject $subject, Permission $permission): bool
    {
        // TODO: Implement the logic to check if the subject has the specified permission.
        return false;
    }
}
