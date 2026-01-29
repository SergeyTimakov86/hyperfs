<?php

declare(strict_types=1);

namespace HyperfTest\Unit\Domain\Model\Account;

use App\Domain\Model\Account\Exception\IngameCorporationCanNotBeEmpty;
use App\Domain\Model\Account\IngameCorporation;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(IngameCorporation::class)]
final class IngameCorporationTest extends TestCase
{
    #[Test]
    #[DataProvider('invalidValuesProvider')]
    public function invalidValueFails(string $value): void
    {
        $this->expectException(IngameCorporationCanNotBeEmpty::class);
        IngameCorporation::of($value);
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
        $vo = IngameCorporation::of($actual);
        $this->assertEquals($expected, $vo->value());
        $this->assertEquals($expected, (string) $vo);
    }

    public static function correctValuesProvider(): array
    {
        return [
            ['test', 'test'],
            [' test ', 'test'],
        ];
    }
}
