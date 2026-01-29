<?php

declare(strict_types=1);

namespace App\Shared\Domain\Value;

use App\Shared\Domain\Value\Exception\IdIsInvalid;
use Stringable;

abstract readonly class Id implements Stringable
{
    protected function __construct(protected int $value)
    {
        if ($this->value < 1) {
            throw new IdIsInvalid(static::class, $this->value);
        }
    }

    public function __toString(): string
    {
        return (string) $this->value();
    }

    public static function of(int $value): static
    {
        /* @phpstan-ignore-next-line */
        return new static($value);
    }

    public function value(): int
    {
        return $this->value;
    }
}
