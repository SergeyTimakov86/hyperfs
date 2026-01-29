<?php

declare(strict_types=1);

namespace App\Infra\Storage\Repository;

use App\Domain\Model\Account\Account;
use App\Domain\Model\Account\AccountId;
use App\Domain\Model\Account\AccountRepository;
use App\Domain\Model\Account\IngameId;
use Hyperf\DbConnection\Db;

final class DatabaseAccounts implements AccountRepository
{
    public function save(Account $account): AccountId
    {
        return AccountId::of(
            Db::table('_account')->insertGetId(self::entityData($account))
        );
    }

    public function update(Account $account): void
    {
        Db::table('_account')
            ->where('id', $account->id())
            ->update(self::entityData($account));
    }

    public function delete(AccountId $id): void
    {
        Db::table('_account')->where('id', $id)->delete();
    }

    public function exists(AccountId $id): bool
    {
        return Db::table('_account')->where('id', $id)->exists();
    }

    public function existsByIngameId(IngameId $ingameId): bool
    {
        return Db::table('_account')->where('ingame_id', $ingameId)->exists();
    }

    private static function entityData(Account $account, bool $withId = false): array
    {
        $data = $account->asArray();
        if (!$withId) {
            unset($data['id']);
        }
        return $data;
    }
}
