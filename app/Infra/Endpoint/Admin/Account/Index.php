<?php

declare(strict_types=1);

namespace App\Infra\Endpoint\Admin\Account;

use App\Domain\Model\Game;
use App\Domain\Model\GameSlug;
use App\Infra\AdminEndpoint;
use App\Shared\Presentation\Grid\Column;
use App\Shared\Presentation\Grid\Columns;
use App\Shared\Presentation\Grid\Grid;
use App\Shared\Presentation\Query\Sort;
use Hyperf\DbConnection\Db;

final class Index extends AdminEndpoint
{
    protected function payload(): array
    {
        if ($gameSlug = $this->request->query('gameSlug')) {
            $game = Game::fromSlug($gameSlug);

            if ($this->isPost()) {
                // ...
            }

            $sort = Sort::fromRequest($this->request, 'updated_at', $columns = new Columns(
                new Column('ingame_id', 'ID')->sortable(),
                new Column('ingame_name', 'Ingame Name')->sortable(),
                new Column('funpay_name', 'FunPay Name')->sortable(),
                new Column('discord', 'Discord'),
                new Column('telegram', 'Telegram'),
                new Column('updated_at', 'Updated At')->sortable(),
            ));

            $accounts = Db::select(
                "
                    SELECT * FROM _account
                    WHERE game_id = ?
                    ORDER BY {$sort->field()} {$sort->order()->value}
                    LIMIT 100
                ",
                [$game->id()]
            );

            $grid = new Grid($columns, $accounts, $sort, $this->request->getUri()->getQuery());
        }

        return [
            'gameSlug' => $gameSlug,
            'games' => GameSlug::slugs2games(),
            'grid' => $grid ?? Grid::empty(),
        ];
    }

    protected static function rendering(): bool
    {
        return true;
    }
}
