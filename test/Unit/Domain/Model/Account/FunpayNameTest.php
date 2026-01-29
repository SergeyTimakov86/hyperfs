<?php

declare(strict_types=1);

namespace HyperfTest\Unit\Domain\Model\Account;

use App\Domain\Model\Account\Exception\FunpayNameCanNotBeEmpty;
use App\Domain\Model\Account\FunpayName;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(FunpayName::class)]
final class FunpayNameTest extends TestCase
{
    #[Test]
    #[DataProvider('invalidValuesProvider')]
    public function invalidValueFails(string $name): void
    {
        $this->expectException(FunpayNameCanNotBeEmpty::class);
        FunpayName::of($name);
    }

    public static function invalidValuesProvider(): array
    {
        return [
            'empty' => [''],
            'whitespace' => ['   '],
        ];
    }

    #[Test]
    #[DataProvider('correctValuesProvider')]
    public function correctValue(string $actual, string $expected): void
    {
        $name = FunpayName::of($actual);
        $this->assertEquals($expected, $name->value());
        $this->assertEquals($expected, (string) $name);
    }

    public static function correctValuesProvider(): array
    {
        return [
            ['test', 'test'],
            [' test ', 'test'],
            ['TEST ', 'TEST'],
        ];
    }
}
