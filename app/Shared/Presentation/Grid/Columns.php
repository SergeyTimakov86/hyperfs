<?php

declare(strict_types=1);

namespace App\Shared\Presentation\Grid;

use RuntimeException;

final class Columns
{
    /** @var array<string, Column> */
    private array $columns = [];

    /** @var array<string, string> */
    private array $sortableFields = [];

    public function __construct(Column ...$columns)
    {
        foreach ($columns as $column) {
            $field = $column->field();

            if (isset($this->columns[$field])) {
                throw new RuntimeException("Column '{$field}' already exists");
            }

            if ($column->isSortable()) {
                $this->sortableFields[$field] = $field;
            }

            $this->columns[$field] = $column;
        }
    }

    public function columns(): array
    {
        return $this->columns;
    }

    public function sortableFields(): array
    {
        return $this->sortableFields;
    }
}
