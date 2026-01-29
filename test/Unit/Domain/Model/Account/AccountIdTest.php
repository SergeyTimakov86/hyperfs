<?php

declare(strict_types=1);

namespace HyperfTest\Unit\Domain\Model\Account;

use App\Domain\Model\Account\AccountId;
use App\Shared\Domain\Value\Exception\IdIsInvalid;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(AccountId::class)]
final class AccountIdTest extends TestCase
{
    #[Test]
    #[DataProvider('validIds')]
    public function createsWithValidId(int $value): void
    {
        $id = AccountId::of($value);

        self::assertSame($value, $id->value());
        self::assertSame((string) $value, (string) $id);
    }

    public static function validIds(): array
    {
        return [
            'minimum' => [1],
            'typical' => [42],
            'large' => [999999],
        ];
    }

    #[Test]
    #[DataProvider('invalidIds')]
    public function throwsOnInvalidId(int $value): void
    {
        $this->expectException(IdIsInvalid::class);

        AccountId::of($value);
    }

    public static function invalidIds(): array
    {
        return [
            'zero' => [0],
            'negative' => [-1],
            'largeNegative' => [-999],
        ];
    }
}
