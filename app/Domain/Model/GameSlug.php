<?php

declare(strict_types=1);

namespace App\Domain\Model;

use App\Domain\Exception\GameSlugNotRecognized;
use Stringable;

final readonly class GameSlug implements Stringable
{
    private function __construct(private string $value)
    {
        if (!isset(self::slugs2games()[$this->value])) {
            throw new GameSlugNotRecognized('Invalid game slug: ' . $this->value);
        }
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function game(): Game
    {
        return self::slugs2games()[$this->value];
    }

    public function value(): string
    {
        return $this->value;
    }

    public static function fromString(string $value): self
    {
        return new self(self::slug($value));
    }

    public static function fromGame(Game $game): self
    {
        return new self(self::slug($game->name));
    }

    private static function slug(string $value): string
    {
        return str_replace('_', '-', strtolower($value));
    }

    public static function slugs2games(): array
    {
        static $cache;
        if (!isset($cache)) {
            $cache = [];
            foreach (Game::cases() as $game) {
                $cache[self::slug($game->name)] = $game;
            }
        }

        return $cache;
    }
}
