<?php

declare(strict_types=1);

namespace App\Domain;

use DomainException;

class DomainError extends DomainException
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}
