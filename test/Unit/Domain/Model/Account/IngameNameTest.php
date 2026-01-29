<?php

declare(strict_types=1);

namespace HyperfTest\Unit\Domain\Model\Account;

use App\Domain\Model\Account\Exception\IngameNameCanNotBeEmpty;
use App\Domain\Model\Account\IngameName;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(IngameName::class)]
final class IngameNameTest extends TestCase
{
    #[Test]
    #[DataProvider('invalidValuesProvider')]
    public function invalidValueFails(string $value): void
    {
        $this->expectException(IngameNameCanNotBeEmpty::class);
        IngameName::of($value);
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
        $vo = IngameName::of($actual);
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
