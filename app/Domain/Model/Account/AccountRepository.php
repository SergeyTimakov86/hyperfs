<?php

declare(strict_types=1);

namespace App\Domain\Model\Account;

/**
 * @see \App\Infra\Storage\Repository\DatabaseAccounts
 */
interface AccountRepository
{
    public function save(Account $account): AccountId;

    public function update(Account $account): void;

    public function delete(AccountId $id): void;

    public function exists(AccountId $id): bool;

    public function existsByIngameId(IngameId $ingameId): bool;
}
