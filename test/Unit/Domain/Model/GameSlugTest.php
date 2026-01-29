<?php

declare(strict_types=1);

namespace HyperfTest\Unit\Domain\Model;

use App\Domain\Exception\GameSlugNotRecognized;
use App\Domain\Model\Game;
use App\Domain\Model\GameSlug;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(GameSlug::class)]
final class GameSlugTest extends TestCase
{
    #[Test]
    public function itCanBeCreatedFromValidSlug(): void
    {
        $slug = GameSlug::of('eve-echoes');
        $this->assertSame('eve-echoes', (string)$slug);
        $this->assertSame(Game::EVE_ECHOES, $slug->game());
    }

    #[Test]
    public function itThrowsExceptionOnInvalidSlug(): void
    {
        $this->expectException(GameSlugNotRecognized::class);
        GameSlug::of('invalid-game');
    }

    #[Test]
    public function itCanBeCreatedFromGame(): void
    {
        $slug = GameSlug::fromGame(Game::RAVEN2);
        $this->assertSame('raven2', $slug->value());
    }

    #[Test]
    #[DataProvider('provideTryData')]
    public function itHandlesTryMethods(mixed $input, ?string $expectedValue): void
    {
        $slug = GameSlug::try($input);
        if ($expectedValue === null) {
            $this->assertNull($slug);
        } else {
            $this->assertSame($expectedValue, $slug->value());
        }
    }

    public static function provideTryData(): array
    {
        return [
            'valid string' => ['eve-echoes', 'eve-echoes'],
            'invalid string' => ['unknown', null],
            'non-string' => [123, null],
            'null' => [null, null],
        ];
    }

    #[Test]
    public function itHandlesTryKey(): void
    {
        $data = ['game' => 'eve-echoes', 'other' => 'unknown'];
        
        $this->assertSame('eve-echoes', GameSlug::tryKey($data, 'game')->value());
        $this->assertNull(GameSlug::tryKey($data, 'other'));
        $this->assertNull(GameSlug::tryKey($data, 'missing'));
    }
}
