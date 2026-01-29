<?php

declare(strict_types=1);

namespace App\Shared\Domain\Value\Identity;

use App\Shared\Domain\Primitive\StringValue;
use App\Shared\Domain\Value\Exception\TelegramCanNotBeEmpty;

final class Telegram extends StringValue
{
    public static function validate(string $value): void
    {
        if ($value === '') {
            throw new TelegramCanNotBeEmpty();
        }
    }

    public static function pattern(): string
    {
        return '^[A-Za-z][A-Za-z0-9_]{4,31}$';
    }
}
