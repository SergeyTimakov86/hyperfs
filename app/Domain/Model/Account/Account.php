<?php

declare(strict_types=1);

namespace App\Domain\Model\Account;

use App\Domain\Model\Entity;
use App\Domain\Model\Game;
use App\Shared\Domain\Value\Identity\Discord;
use App\Shared\Domain\Value\Identity\Telegram;

final class Account extends Entity
{
    public function __construct(
        private Game $game,
        private ?AccountId $id = null,
        private ?FunpayName $funpayName = null,
        private ?IngameId $ingameId = null,
        private ?IngameName $ingameName = null,
        private ?IngameCorporation $ingameCorporation = null,
        private ?IngameAlliance $ingameAlliance = null,
        private ?IsSeller $isSeller = null,
        private ?Discord $discord = null,
        private ?Telegram $telegram = null,
    ) {
        $this->isSeller ??= IsSeller::of(false);
    }

    public function asArray(): array
    {
        return [
            'id' => $this->id?->value(),
            'game_id' => $this->game->id(),
            'funpay_name' => $this->funpayName?->value(),
            'ingame_id' => $this->ingameId?->value(),
            'ingame_name' => $this->ingameName?->value(),
            'ingame_corp' => $this->ingameCorporation?->value(),
            'ingame_alliance' => $this->ingameAlliance?->value(),
            'is_seller' => $this->isSeller->value(),
            'discord' => $this->discord?->value(),
            'telegram' => $this->telegram?->value(),
        ];
    }

    public function id(): ?AccountId
    {
        return $this->id;
    }

    public function game(): Game
    {
        return $this->game;
    }

    public function funpayName(): ?FunpayName
    {
        return $this->funpayName;
    }

    public function ingameId(): ?IngameId
    {
        return $this->ingameId;
    }

    public function ingameName(): ?IngameName
    {
        return $this->ingameName;
    }

    public function ingameCorporation(): ?IngameCorporation
    {
        return $this->ingameCorporation;
    }

    public function ingameAlliance(): ?IngameAlliance
    {
        return $this->ingameAlliance;
    }

    public function isSeller(): ?IsSeller
    {
        return $this->isSeller;
    }

    public function discord(): ?Discord
    {
        return $this->discord;
    }

    public function telegram(): ?Telegram
    {
        return $this->telegram;
    }
}
