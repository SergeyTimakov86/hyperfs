<?php

declare(strict_types=1);

namespace App\Infra\Endpoint\Admin\Account;

use App\Infra\AdminEndpoint;
use App\Infra\Storage\Query\DatabaseAccounts;

final class Get extends AdminEndpoint
{
    public function __construct(
        private readonly DatabaseAccounts $accountsQuery
    ) {
    }

    protected function payload(): array
    {
        $id = (int) $this->request->route('id');
        $account = $this->accountsQuery->get($id);

        if (! $account) {
            return [
                'success' => false,
                'message' => 'Account not found',
            ];
        }

        return [
            'success' => true,
            'data' => (array) $account,
        ];
    }
}
