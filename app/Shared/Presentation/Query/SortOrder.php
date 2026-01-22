<?php

declare(strict_types=1);

namespace App\Shared\Presentation\Query;

use App\Shared\RequestParam;
use Hyperf\HttpServer\Contract\RequestInterface;

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

    public static function fromRequest(
        RequestInterface $request,
        self $default = self::DEFAULT,
        string $param = RequestParam::SORT_ORDER
    ): self {
        $notDefault = $default->opposite();
        return $request->query($param, $default->value) === $notDefault->value ? $notDefault : $default;
    }
}
