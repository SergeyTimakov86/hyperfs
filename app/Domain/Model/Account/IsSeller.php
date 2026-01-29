<?php

declare(strict_types=1);

namespace App\Domain\Model\Account;

use Stringable;

/**
 * Boolean wrapper for seller status. Returns '+' for true, '-' for false.
 * @see \HyperfTest\Unit\Domain\Model\Account\IsSellerTest
 */
final readonly class IsSeller implements Stringable
{
    private function __construct(private bool $value)
    {
    }

    public function __toString(): string
    {
        return $this->value ? '+' : '-';
    }

    public static function of(bool $value): self
    {
        return new self($value);
    }

    public static function tryKey(array $array, string $key): ?self
    {
        return self::try($array[$key] ?? null);
    }

    public static function try(mixed $value): ?self
    {
        if ($value === null) {
            return null;
        }

        if (is_bool($value)) {
            return self::of($value);
        }

        if ($value === 'on' || $value === '1' || $value === 1 || $value === 'true') {
            return self::of(true);
        }

        return self::of(false);
    }

    public function value(): bool
    {
        return $this->value;
    }
}
