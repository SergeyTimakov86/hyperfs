<?php

declare(strict_types=1);

namespace App\Infra\Endpoint\Admin\Account;

use App\Domain\Model\Account\Account;
use App\Domain\Model\Account\AccountRepository;
use App\Domain\Model\Account\FunpayName;
use App\Domain\Model\Account\IngameAlliance;
use App\Domain\Model\Account\IngameCorporation;
use App\Domain\Model\Account\IngameId;
use App\Domain\Model\Account\IngameName;
use App\Domain\Model\Account\IsSeller;
use App\Domain\Model\Game;
use App\Infra\AdminEndpoint;
use App\Shared\Domain\DomainError;
use App\Shared\Domain\Value\Identity\Discord;
use App\Shared\Domain\Value\Identity\Telegram;
use Hyperf\Di\Annotation\Inject;

/**
 * Creates new Account entity from POST form data.
 *
 * Route: POST /admin/accounts
 * Input: POST form data with account fields (game_id, funpay_name, ingame_id, etc.).
 * Output: JSON {success: true, data: {id, ...account fields, updated_at}}.
 *
 * VOs handle validation via tryKey(); invalid input throws DomainError.
 *
 * @see \App\Infra\Endpoint\Admin\Account\Update Similar logic for updates
 * @see \App\Domain\Model\Account\Account Entity definition
 * @see \App\Domain\Model\Account\AccountRepository::save() Persistence
 */
final class Add extends AdminEndpoint
{
    #[Inject]
    private AccountRepository $accounts;

    protected function payload(): array
    {
        $data = $this->request->post();

        $account = new Account(
            game: Game::from((int) ($data['game_id'] ?? 0)),
            id: null,
            funpayName: FunpayName::tryKey($data, 'funpay_name'),
            ingameId: $ingameId = IngameId::tryKey($data, 'ingame_id'),
            ingameName: IngameName::tryKey($data, 'ingame_name'),
            ingameCorporation: IngameCorporation::tryKey($data, 'ingame_corporation'),
            ingameAlliance: IngameAlliance::tryKey($data, 'ingame_alliance'),
            isSeller: IsSeller::tryKey($data, 'is_seller'),
            discord: Discord::tryKey($data, 'discord'),
            telegram: Telegram::tryKey($data, 'telegram'),
        );

        if ($ingameId && $this->accounts->existsByIngameId($ingameId)) {
            throw new DomainError('Account with the same ingame ID already exists');
        }

        $id = $this->accounts->save($account);

        return [
            'success' => true,
            'data' => array_merge($account->asArray(), [
                'id' => $id->value(),
                'updated_at' => date('Y-m-d H:i:s'),
            ]),
        ];
    }
}
