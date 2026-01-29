<?php

declare(strict_types=1);

namespace App\Shared\Presentation\Grid;

use App\Shared\Grid\Column as ColumnDefinition;
use App\Shared\Grid\ColumnType;

final readonly class Column
{
    public function __construct(
        private ColumnDefinition $column,
        private string $uriQuery = '',
        private string $ariaSort = 'none',
        private bool $isCurrentlySorted = false,
    ) {
    }

    public function field(): string
    {
        return $this->column->field();
    }

    public function label(): string
    {
        return $this->column->label();
    }

    public function type(): ColumnType
    {
        return $this->column->type();
    }

    public function isSortable(): bool
    {
        return $this->column->isSortable();
    }

    public function uriQuery(): string
    {
        return $this->uriQuery;
    }

    public function ariaSort(): string
    {
        return $this->ariaSort;
    }

    public function sorted(): bool
    {
        return $this->isCurrentlySorted;
    }
}
