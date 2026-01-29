<?php

declare(strict_types=1);

namespace App\Infra\Storage\Query;

use App\Domain\Model\Game;
use App\Shared\Presentation\Query\Sort;
use Hyperf\DbConnection\Db;

final readonly class DatabaseAccounts
{
    /**
     * @return array<int, object>
     */
    public function byGame(Game $game, Sort $sort, int $limit = 100): array
    {
        return Db::select(
            "
                SELECT * FROM _account
                WHERE game_id = ?
                ORDER BY {$sort->field()} {$sort->order()->value}
                LIMIT {$limit}
            ",
            [$game->id()]
        );
    }

    /**
     * Get single account by ID for read/display purposes.
     */
    public function get(int $id): ?object
    {
        return Db::table('_account')->where('id', $id)->first();
    }
}
