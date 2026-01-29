<?php

declare(strict_types=1);

namespace App\Domain\Model\Account;

use App\Domain\Model\Account\Exception\IngameIdCanNotBeEmpty;
use App\Shared\Domain\Primitive\StringValue;

/**
 * Ingame ID VO.
 * @see \HyperfTest\Unit\Domain\Model\Account\IngameIdTest
 */
final class IngameId extends StringValue
{
    public static function validate(string $value): void
    {
        if ($value === '') {
            throw new IngameIdCanNotBeEmpty();
        }
    }

    public static function pattern(): string
    {
        return '^[0-9]{11}$';
    }
}
