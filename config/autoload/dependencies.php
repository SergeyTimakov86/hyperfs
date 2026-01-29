<?php

declare(strict_types=1);

use App\Domain\Model\Account\AccountRepository;
use App\Infra\Storage\Repository\DatabaseAccounts;

return [
    AccountRepository::class => DatabaseAccounts::class,
];
