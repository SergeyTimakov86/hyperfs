<?php

declare(strict_types=1);

namespace App\Shared\Presentation\Grid;

use App\Shared\Grid\Columns;
use App\Shared\Infra\RequestParam;
use App\Shared\Presentation\Query\Sort;

final class GridSort
{
    private string $baseQuery;
    private Sort $sort;

    public function __construct(
        string $defaultField,
        array $requestQuery,
        private readonly Columns $columns,
        private readonly bool $onClientSide = true
    ) {
        if ($this->onClientSide) {
            $this->sort = Sort::default($defaultField);
            return;
        }

        $this->sort = Sort::fromRequestQuery(
            $defaultField,
            $requestQuery,
            $this->columns
        );

        unset(
            $requestQuery[RequestParam::SORT_FIELD],
            $requestQuery[RequestParam::SORT_ORDER]
        );

        $queryString = http_build_query($requestQuery);
        $this->baseQuery = '?' . ($queryString !== '' ? $queryString . '&' : '') . RequestParam::SORT_FIELD . '=';
    }

    public function uri(string $sortParam, bool $isCurrentlySorted): string
    {
        if ($this->onClientSide()) {
            return '';
        }

        $uri = $this->baseQuery . $sortParam;

        if ($isCurrentlySorted) {
            $uri .= '&' . RequestParam::SORT_ORDER . '=' . $this->sort->order()->opposite()->value;
        }

        return $uri;
    }

    public function onClientSide(): bool
    {
        return $this->onClientSide;
    }

    public function sort(): Sort
    {
        return $this->sort;
    }

    public function columns(): Columns
    {
        return $this->columns;
    }
}
