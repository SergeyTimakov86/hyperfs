<?php

declare(strict_types=1);

namespace App\Shared\Domain\Value\Identity;

use App\Shared\Domain\Primitive\StringValue;
use App\Shared\Domain\Value\Exception\DiscordCanNotBeEmpty;

final class Discord extends StringValue
{
    public static function validate(string $value): void
    {
        if ($value === '') {
            throw new DiscordCanNotBeEmpty();
        }
    }

    public static function pattern(): string
    {
        return '^[a-z0-9](?:[a-z0-9_\.]{0,30}[a-z0-9_])?$';
    }
}
