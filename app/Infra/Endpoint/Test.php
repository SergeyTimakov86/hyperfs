<?php

declare(strict_types=1);

namespace App\Infra\Endpoint;

use App\Infra\Endpoint;
use Hyperf\DbConnection\Db;

final class Test extends Endpoint
{
    public function __invoke(): array
    {
        //return ['[{"currency_id":1,"rate":"1.00000000","updated_at":"2026-01-11 14:56:11"},{"currency_id":2,"rate":"2.00000000","updated_at":"2026-01-11 14:56:11"},{"currency_id":3,"rate":"3.00000000","updated_at":"2026-01-11 14:56:11"},{"currency_id":4,"rate":"4.00000000","updated_at":"2026-01-11 14:56:11"},{"currency_id":5,"rate":"5.00000000","updated_at":"2026-01-11 14:56:11"}]'];
        return Db::select('SELECT * FROM currency_rate');
    }
}
