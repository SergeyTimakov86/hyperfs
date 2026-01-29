<?php

declare(strict_types=1);

namespace HyperfTest\Unit\Shared\Domain\Value\Identity;

use App\Shared\Domain\DomainError;
use App\Shared\Domain\Value\Exception\DiscordCanNotBeEmpty;
use App\Shared\Domain\Value\Identity\Discord;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class DiscordTest extends TestCase
{
    #[Test]
    #[DataProvider('emptyValuesProvider')]
    public function emptyValueFails(string $value): void
    {
        $this->expectException(DiscordCanNotBeEmpty::class);
        Discord::of($value);
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
        Discord::of($value);
    }

    public static function invalidPatternProvider(): array
    {
        return [
            'with hash' => ['user#1234'],
            'too long' => [str_repeat('a', 33)],
            'invalid start' => ['_user'],
            'uppercase' => ['User'],
        ];
    }

    #[Test]
    #[DataProvider('correctValuesProvider')]
    public function correctValue(string $actual, string $expected): void
    {
        $vo = Discord::of($actual);
        $this->assertEquals($expected, $vo->value());
        $this->assertEquals($expected, (string) $vo);
    }

    public static function correctValuesProvider(): array
    {
        return [
            ['user', 'user'],
            ['user_name', 'user_name'],
            ['user.name', 'user.name'],
            ['u123', 'u123'],
            ['  user123  ', 'user123'],
        ];
    }
}
