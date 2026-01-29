<?php

declare(strict_types=1);

namespace HyperfTest\Unit\Domain\Model\Account;

use App\Domain\Model\Account\Exception\IngameIdCanNotBeEmpty;
use App\Domain\Model\Account\IngameId;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(IngameId::class)]
final class IngameIdTest extends TestCase
{
    #[Test]
    #[DataProvider('invalidValuesProvider')]
    public function invalidValueFails(string $value): void
    {
        $this->expectException(IngameIdCanNotBeEmpty::class);
        IngameId::of($value);
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
        $id = IngameId::of($actual);
        $this->assertEquals($expected, $id->value());
        $this->assertEquals($expected, (string) $id);
    }

    public static function correctValuesProvider(): array
    {
        return [
            ['12345678901', '12345678901'],
            [' 98765432109 ', '98765432109'],
            ['00000000001', '00000000001'],
        ];
    }
}
