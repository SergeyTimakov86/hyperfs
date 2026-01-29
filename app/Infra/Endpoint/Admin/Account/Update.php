<?php

declare(strict_types=1);

namespace App\Infra\Endpoint\Admin\Account;

use App\Domain\Model\Account\Account;
use App\Domain\Model\Account\AccountId;
use App\Domain\Model\Account\AccountRepository;
use App\Domain\Model\Account\FunpayName;
use App\Domain\Model\Account\IngameAlliance;
use App\Domain\Model\Account\IngameCorporation;
use App\Domain\Model\Account\IngameId;
use App\Domain\Model\Account\IngameName;
use App\Domain\Model\Account\IsSeller;
use App\Domain\Model\Game;
use App\Infra\AdminEndpoint;
use App\Shared\Domain\Value\Identity\Discord;
use App\Shared\Domain\Value\Identity\Telegram;
use Hyperf\Di\Annotation\Inject;

final class Update extends AdminEndpoint
{
    #[Inject]
    private AccountRepository $accounts;

    protected function payload(): array
    {
        $data = $this->request->post();
        $id = AccountId::of((int) ($this->request->route('id') ?? $data['id'] ?? 0));

        $account = new Account(
            id: $id,
            game: Game::from((int) ($data['game_id'] ?? 0)),
            funpayName: FunpayName::tryKey($data, 'funpay_name'),
            ingameId: IngameId::tryKey($data, 'ingame_id'),
            ingameName: IngameName::tryKey($data, 'ingame_name'),
            ingameCorporation: IngameCorporation::tryKey($data, 'ingame_corporation'),
            ingameAlliance: IngameAlliance::tryKey($data, 'ingame_alliance'),
            isSeller: IsSeller::tryKey($data, 'is_seller'),
            discord: Discord::tryKey($data, 'discord'),
            telegram: Telegram::tryKey($data, 'telegram'),
        );

        $this->accounts->update($account);

        return [
            'success' => true,
            'data' => array_merge($account->asArray(), [
                'updated_at' => date('Y-m-d H:i:s'),
            ]),
        ];
    }
}
