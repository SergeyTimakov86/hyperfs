<?php

declare(strict_types=1);

namespace App\Domain\Model\Account;

use App\Domain\Model\Account\Exception\IngameCorporationCanNotBeEmpty;
use App\Shared\Domain\Primitive\StringValue;

/**
 * Ingame Corporation Name VO.
 * @see \HyperfTest\Unit\Domain\Model\Account\IngameCorporationTest
 */
final class IngameCorporation extends StringValue
{
    public static function validate(string $value): void
    {
        if ($value === '') {
            throw new IngameCorporationCanNotBeEmpty();
        }
    }

    public static function maxLength(): ?int
    {
        return 4;
    }
}
