<?php

declare(strict_types=1);

namespace App\Domain\Model\Account;

use App\Domain\Model\Account\Exception\FunpayNameCanNotBeEmpty;
use App\Shared\Domain\Primitive\StringValue;

/**
 * Funpay User Name VO.
 * @see \HyperfTest\Unit\Domain\Model\Account\FunpayNameTest
 */
final class FunpayName extends StringValue
{
    public static function validate(string $value): void
    {
        if ($value === '') {
            throw new FunpayNameCanNotBeEmpty();
        }
    }

    public static function minLength(): int
    {
        return 3;
    }

    public static function maxLength(): int
    {
        return 100;
    }
}
