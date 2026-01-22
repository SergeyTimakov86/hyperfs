<?php

declare(strict_types=1);

namespace App\Infra\Endpoint\Admin\Account;

use App\Domain\Model\Game;
use App\Infra\AdminEndpoint;
use Hyperf\DbConnection\Db;

final class Add extends AdminEndpoint
{
    protected function payload(): array
    {
        $data = $this->request->post();

        $id = Db::table('_account')->insertGetId(
            $entity = [
                'game_id' => Game::from((int) ($data['game_id'] ?? 0))->id(),
                'ingame_id' => $data['ingame_id'] ?? null,
                'ingame_name' => $data['ingame_name'] ?? null,
                'funpay_name' => $data['funpay_name'],
                'ingame_corp' => $data['ingame_corp'] ?? null,
                'ingame_alliance' => $data['ingame_alliance'] ?? null,
                'is_seller' => !empty($data['is_seller']),
                'discord' => $data['discord'] ?? null,
                'telegram' => $data['telegram'] ?? null,
            ]
        );

        return [
            'success' => true,
            'data' => array_merge($entity, [
                'id' => $id,
                'updated_at' => date('Y-m-d H:i:s'),
            ]),
        ];
    }
}
