<?php

declare(strict_types=1);

namespace App\Domain\Model;

enum Game: int
{
    case EVE_ECHOES = 1;
    case RAVEN2 = 2;
    case AION_2 = 3;

    public function title(): string
    {
        return match ($this) {
            self::EVE_ECHOES => 'EVE Echoes',
            self::RAVEN2 => 'RAVEN2',
            self::AION_2 => 'Aion 2',
        };
    }

    public static function fromSlug(string $slug): self
    {
        return GameSlug::fromString($slug)->game();
    }

    public function slug(): string
    {
        return GameSlug::fromGame($this)->value();
    }

    public function id(): int
    {
        return $this->value;
    }
}
