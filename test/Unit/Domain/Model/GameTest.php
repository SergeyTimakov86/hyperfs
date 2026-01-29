<?php

declare(strict_types=1);

namespace HyperfTest\Unit\Domain\Model;

use App\Domain\Model\Game;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(Game::class)]
final class GameTest extends TestCase
{
    #[Test]
    public function itHasCorrectTitles(): void
    {
        $this->assertSame('EVE Echoes', Game::EVE_ECHOES->title());
        $this->assertSame('RAVEN2', Game::RAVEN2->title());
        $this->assertSame('Aion 2', Game::AION_2->title());
    }

    #[Test]
    public function itCanBeCreatedFromSlug(): void
    {
        $this->assertSame(Game::EVE_ECHOES, Game::fromSlug('eve-echoes'));
        $this->assertSame(Game::RAVEN2, Game::fromSlug('raven2'));
    }

    #[Test]
    public function itReturnsSlug(): void
    {
        $this->assertSame('eve-echoes', Game::EVE_ECHOES->slug());
        $this->assertSame('raven2', Game::RAVEN2->slug());
    }

    #[Test]
    public function itReturnsId(): void
    {
        $this->assertSame(1, Game::EVE_ECHOES->id());
    }
}
