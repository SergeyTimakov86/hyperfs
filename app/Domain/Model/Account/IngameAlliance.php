<?php

declare(strict_types=1);

namespace App\Domain\Model\Account;

use App\Domain\Model\Account\Exception\IngameAllianceCanNotBeEmpty;
use App\Shared\Domain\Primitive\StringValue;

/**
 * Ingame Alliance Name VO.
 * @see \HyperfTest\Unit\Domain\Model\Account\IngameAllianceTest
 */
final class IngameAlliance extends StringValue
{
    public static function validate(string $value): void
    {
        if ($value === '') {
            throw new IngameAllianceCanNotBeEmpty();
        }
    }

    public static function maxLength(): ?int
    {
        return 4;
    }
}
