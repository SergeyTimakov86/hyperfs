<?php

declare(strict_types=1);

namespace App\Shared\Presentation\Query;

use App\Shared\Infra\RequestParam;

/**
 * UI Sort Direction.
 * @see \HyperfTest\Unit\Shared\Presentation\Query\SortOrderTest
 */
enum SortOrder: string
{
    case ASC = 'asc';
    case DESC = 'desc';

    public const self DEFAULT = self::ASC;

    public function isAscending(): bool
    {
        return $this === self::ASC;
    }

    public function opposite(): self
    {
        return $this === self::ASC ? self::DESC : self::ASC;
    }

    /**
     * Factory from query array.
     * Extracts 'dir' (RequestParam::SORT_ORDER). 
     * If value matches opposite of $default, returns it; otherwise returns $default.
     */
    public static function fromRequestQuery(
        array $requestQuery,
        self $default = self::DEFAULT,
        string $param = RequestParam::SORT_ORDER
    ): self {
        $notDefault = $default->opposite();
        $value = $requestQuery[$param] ?? $default->value;
        return $value === $notDefault->value ? $notDefault : $default;
    }
}
