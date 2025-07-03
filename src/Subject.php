<?php

declare(strict_types=1);

namespace Guard;

interface Subject
{
    /**
     * @return iterable<Role>
     */
    public function getRoles(): iterable;
}
