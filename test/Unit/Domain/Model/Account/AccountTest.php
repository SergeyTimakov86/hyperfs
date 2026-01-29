<?php

declare(strict_types=1);

namespace HyperfTest\Unit\Domain\Model\Account;

use App\Domain\Model\Account\Account;
use App\Domain\Model\Account\AccountId;
use App\Domain\Model\Account\FunpayName;
use App\Domain\Model\Account\IngameAlliance;
use App\Domain\Model\Account\IngameCorporation;
use App\Domain\Model\Account\IngameId;
use App\Domain\Model\Account\IngameName;
use App\Domain\Model\Account\IsSeller;
use App\Domain\Model\Game;
use App\Shared\Domain\Value\Identity\Discord;
use App\Shared\Domain\Value\Identity\Telegram;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
final class AccountTest extends TestCase
{
    #[Test]
    public function canCreateAccountWithOnlyGame(): void
    {
        $game = Game::EVE_ECHOES;

        $account = new Account(
            game: $game
        );

        $this->assertEquals($game, $account->game());
        $this->assertNull($account->funpayName());
        $this->assertNull($account->ingameId());
        $this->assertNull($account->id());
    }

    #[Test]
    public function canCreateAccountWithRequiredFields(): void
    {
        $game = Game::EVE_ECHOES;
        $funpayName = FunpayName::of('Seller1');

        $account = new Account(
            game: $game,
            funpayName: $funpayName
        );

        $this->assertEquals($game, $account->game());
        $this->assertEquals($funpayName, $account->funpayName());
        $this->assertNull($account->ingameId());
        $this->assertNull($account->id());
    }

    #[Test]
    public function canCreateFullAccount(): void
    {
        $game = Game::RAVEN2;
        $funpayName = FunpayName::of('Seller2');
        $ingameId = IngameId::of('12345678901');
        $ingameName = IngameName::of('Hero');
        $ingameCorporation = IngameCorporation::of('CORP');
        $ingameAlliance = IngameAlliance::of('ALLY');
        $isSeller = IsSeller::of(true);
        $discord = Discord::of('user1234');
        $telegram = Telegram::of('user_name');
        $id = AccountId::of(42);

        $account = new Account(
            game: $game,
            funpayName: $funpayName,
            ingameId: $ingameId,
            ingameName: $ingameName,
            ingameCorporation: $ingameCorporation,
            ingameAlliance: $ingameAlliance,
            isSeller: $isSeller,
            discord: $discord,
            telegram: $telegram,
            id: $id
        );

        $this->assertEquals($id->value(), $account->id()->value());
        $this->assertEquals($game, $account->game());
        $this->assertEquals($funpayName, $account->funpayName());
        $this->assertEquals($ingameId, $account->ingameId());

        $asArray = $account->asArray();
        $this->assertEquals($game->id(), $asArray['game_id']);
        $this->assertEquals('Seller2', $asArray['funpay_name']);
        $this->assertEquals('12345678901', $asArray['ingame_id']);
        $this->assertEquals('Hero', $asArray['ingame_name']);
        $this->assertEquals('CORP', $asArray['ingame_corp']);
        $this->assertEquals('ALLY', $asArray['ingame_alliance']);
        $this->assertTrue($asArray['is_seller']);
        $this->assertEquals('user1234', $asArray['discord']);
        $this->assertEquals('user_name', $asArray['telegram']);
    }

    #[Test]
    public function jsonSerialization(): void
    {
        $account = new Account(
            game: Game::AION_2,
            funpayName: FunpayName::of('Test')
        );

        $json = json_encode($account);
        $this->assertIsString($json);
        $data = json_decode($json, true);

        $this->assertEquals($account->asArray(), $data);
    }
}
