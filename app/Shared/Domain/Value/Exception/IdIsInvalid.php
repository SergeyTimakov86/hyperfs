<?php

declare(strict_types=1);

namespace App\Shared\Domain\Value\Exception;

use App\Shared\Domain\DomainError;

final class IdIsInvalid extends DomainError
{
    public function __construct(string $class, int $given)
    {
        parent::__construct("Invalid ID for {$class}: '{$given}'");
    }
}
