<?php

declare(strict_types=1);

namespace App\Infra\Endpoint\Admin;

use App\Domain\Model\Game;
use App\Domain\Model\GameSlug;
use App\Infra\AdminEndpoint;
use Hyperf\DbConnection\Db;

final class B extends AdminEndpoint
{
    protected function payload(): array
    {

        return [];
    }
}
