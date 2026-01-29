<?php

declare(strict_types=1);

namespace App\Shared\Domain\Primitive;

use App\Shared\Domain\DomainError;
use Stringable;

/**
 * Base for String Value Objects.
 * Automatically trims input and triggers static::validate().
 */
abstract class StringValue implements Stringable
{
    private int $length;

    protected function __construct(protected string $value)
    {
        $this->value = trim($value);
        static::validate($this->value);

        $this->length = strlen($this->value);
        $class = shortClassName(static::class);

        if (($minLengthAllowed = static::minLength()) !== null) {
            if ($minLengthAllowed > $this->length) {
                $error = sprintf(
                    '%s must be at least %d characters long',
                    $class,
                    $minLengthAllowed
                );
            }
        }

        if (($maxLengthAllowed = static::maxLength()) !== null) {
            if ($maxLengthAllowed < $this->length) {
                $error = sprintf(
                    '%s must be at most %d characters long',
                    $class,
                    $maxLengthAllowed
                );
            }
        }

        if (($pattern = static::pattern()) !== null) {
            if (!preg_match('/' . $pattern . '/', $this->value)) {
                $error = sprintf('%s is invalid', $class);
            }
        }

        if (isset($error)) {
            throw new DomainError($error);
        }
    }

    public function __toString(): string
    {
        return $this->value();
    }

    public static function minLength(): ?int
    {
        return null;
    }

    public static function maxLength(): ?int
    {
        return null;
    }

    public static function pattern(): ?string
    {
        return null;
    }

    /**
     * MUST throw DomainError if value is invalid.
     */
    abstract public static function validate(string $value): void;

    /**
     * Factory from array key. Returns null if key missing, null, or empty string.
     */
    public static function tryKey(array $array, string $key): ?static
    {
        if (!array_key_exists($key, $array) || $array[$key] === null || $array[$key] === '') {
            return null;
        }

        return self::try($array[$key]);
    }

    /**
     * Factory from mixed input. Returns null if not a string.
     */
    public static function try(mixed $value): ?static
    {
        return is_string($value) ? static::of($value) : null;
    }

    /**
     * Forced factory.
     */
    public static function of(string $value): static
    {
        /* @phpstan-ignore-next-line */
        return new static($value);
    }

    public function value(): string
    {
        return $this->value;
    }

    public function length(): int
    {
        return $this->length;
    }
}
