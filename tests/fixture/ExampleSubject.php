<?php

declare(strict_types=1);

namespace Guard\Tests\Fixture;

use Guard\AsSubject;
use Guard\Subject;
use Override;

final readonly class ExampleSubject implements Subject
{
    use AsSubject;

    #[Override]
    public function getRoles(): iterable
    {
        return [];
    }
}
