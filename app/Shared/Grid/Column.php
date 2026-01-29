<?php

declare(strict_types=1);

namespace App\Shared\Grid;

/**
 * Data column definition for Grids.
 * @see \HyperfTest\Unit\Shared\Grid\ColumnTest
 */
final readonly class Column
{
    public function __construct(
        private string $field,
        private string $label,
        private bool $isSortable = false,
        private ColumnType $type = ColumnType::STRING,
    ) {
    }

    public function field(): string
    {
        return $this->field;
    }

    public function label(): string
    {
        return $this->label;
    }

    public function type(): ColumnType
    {
        return $this->type;
    }

    public function isSortable(): bool
    {
        return $this->isSortable;
    }
}
