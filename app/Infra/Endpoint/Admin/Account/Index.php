<?php

declare(strict_types=1);

namespace App\Infra\Endpoint\Admin\Account;

use App\Domain\Model\Game;
use App\Domain\Model\GameSlug;
use App\Infra\AdminEndpoint;
use App\Infra\Storage\Query\DatabaseAccounts;
use App\Shared\Grid\Column;
use App\Shared\Grid\Columns;
use App\Shared\Grid\ColumnType;
use App\Shared\Presentation\Grid\Grid;
use App\Shared\Presentation\Grid\GridSort;
use App\Shared\Presentation\Query\Sort;

final class Index extends AdminEndpoint
{
    private const bool GRID_SORT_ON_CLIENT = true;

    public function __construct(
        private readonly DatabaseAccounts $accountsQuery
    ) {
    }

    protected function payload(): array
    {
        return [
            'gameSlug' => $gameSlug = $this->request->query('gameSlug'),
            'games' => GameSlug::slugs2games(),
            'grid' => !$gameSlug
                ? Grid::empty()
                : new Grid(
                    sort: $gridSort = new GridSort(
                        'updated_at',
                        $this->request->getQueryParams(),
                        new Columns(
                            new Column('ingame_id', 'ID', isSortable: true, type: ColumnType::NUMERIC),
                            new Column('ingame_name', 'Ingame Name', isSortable: true),
                            new Column('ingame_corp', 'Corporation'),
                            new Column('ingame_alliance', 'Alliance'),
                            new Column('funpay_name', 'FunPay Name', isSortable: true),
                            new Column('is_seller', 'Seller'),
                            new Column('discord', 'Discord', isSortable: true),
                            new Column('telegram', 'Telegram'),
                            new Column('updated_at', 'Updated At', isSortable: true, type: ColumnType::DATE),
                        ),
                        self::GRID_SORT_ON_CLIENT
                    ),
                    rows: $this->accountsQuery->byGame(
                        Game::fromSlug($gameSlug),
                        $gridSort->sort()
                    ),
                ),
        ];
    }

    protected static function rendering(): bool
    {
        return true;
    }
}
