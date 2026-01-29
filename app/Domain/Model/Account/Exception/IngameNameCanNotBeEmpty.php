<?php

declare(strict_types=1);

namespace App\Domain\Model\Account\Exception;

use App\Shared\Domain\DomainError;

final class IngameNameCanNotBeEmpty extends DomainError
{
    public const string MESSAGE = 'Ingame name cannot be empty';

    public function __construct()
    {
        parent::__construct(self::MESSAGE);
    }
}
