<?php

declare(strict_types=1);

namespace App\Shared\Domain\Value\Exception;

use App\Shared\Domain\DomainError;

final class DiscordCanNotBeEmpty extends DomainError
{
    public function __construct()
    {
        parent::__construct('Discord can not be empty');
    }
}
