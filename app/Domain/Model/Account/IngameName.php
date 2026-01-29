<?php

declare(strict_types=1);

namespace App\Domain\Model\Account;

use App\Domain\Model\Account\Exception\IngameNameCanNotBeEmpty;
use App\Shared\Domain\Primitive\StringValue;

/**
 * Ingame Character Name VO.
 * @see \HyperfTest\Unit\Domain\Model\Account\IngameNameTest
 */
final class IngameName extends StringValue
{
    public static function validate(string $value): void
    {
        if ($value === '') {
            throw new IngameNameCanNotBeEmpty();
        }
    }

    public static function maxLength(): ?int
    {
        return 100;
    }
}
