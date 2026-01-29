<?php

declare(strict_types=1);

namespace App\Shared\Domain\Value\Exception;

use App\Shared\Domain\DomainError;

final class TelegramCanNotBeEmpty extends DomainError
{
    public function __construct()
    {
        parent::__construct('Telegram can not be empty');
    }
}
