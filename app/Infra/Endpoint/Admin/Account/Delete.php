<?php

declare(strict_types=1);

namespace App\Infra\Endpoint\Admin\Account;

use App\Domain\Model\Account\AccountId;
use App\Domain\Model\Account\AccountRepository;
use App\Infra\AdminEndpoint;
use Hyperf\Di\Annotation\Inject;

final class Delete extends AdminEndpoint
{
    #[Inject]
    private AccountRepository $accounts;

    protected function payload(): array
    {
        $id = AccountId::of((int) $this->request->route('id'));
        $this->accounts->delete($id);

        return [
            'success' => true,
            'data' => ['id' => $id->value()],
        ];
    }
}
