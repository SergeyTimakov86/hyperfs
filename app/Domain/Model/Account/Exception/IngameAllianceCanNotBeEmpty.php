<?php

declare(strict_types=1);

namespace App\Domain\Model\Account\Exception;

use App\Shared\Domain\DomainError;

final class IngameAllianceCanNotBeEmpty extends DomainError
{
    public const string MESSAGE = 'Ingame alliance cannot be empty';

    public function __construct()
    {
        parent::__construct(self::MESSAGE);
    }
}
