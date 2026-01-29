<?php

declare(strict_types=1);

namespace HyperfTest\Unit\Shared\Domain\Value\Identity;

use App\Shared\Domain\DomainError;
use App\Shared\Domain\Value\Exception\TelegramCanNotBeEmpty;
use App\Shared\Domain\Value\Identity\Telegram;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class TelegramTest extends TestCase
{
    #[Test]
    #[DataProvider('emptyValuesProvider')]
    public function emptyValueFails(string $value): void
    {
        $this->expectException(TelegramCanNotBeEmpty::class);
        Telegram::of($value);
    }

    public static function emptyValuesProvider(): array
    {
        return [
            'empty' => [''],
            'whitespace' => ['   '],
        ];
    }

    #[Test]
    #[DataProvider('invalidPatternProvider')]
    public function invalidPatternFails(string $value): void
    {
        $this->expectException(DomainError::class);
        Telegram::of($value);
    }

    public static function invalidPatternProvider(): array
    {
        return [
            'too short' => ['tele'],
            'too long' => [str_repeat('a', 33)],
            'invalid start with digit' => ['1telegram'],
            'invalid start with underscore' => ['_telegram'],
            'invalid character' => ['tele-gram'],
            'with @' => ['@username'],
        ];
    }

    #[Test]
    #[DataProvider('correctValuesProvider')]
    public function correctValue(string $actual, string $expected): void
    {
        $vo = Telegram::of($actual);
        $this->assertEquals($expected, $vo->value());
        $this->assertEquals($expected, (string) $vo);
    }

    public static function correctValuesProvider(): array
    {
        return [
            ['username', 'username'],
            ['User_Name', 'User_Name'],
            ['  username123  ', 'username123'],
            ['A1234567890_', 'A1234567890_'],
        ];
    }
}
