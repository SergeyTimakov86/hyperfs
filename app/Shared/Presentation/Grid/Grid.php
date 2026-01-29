<?php

declare(strict_types=1);

namespace App\Shared\Presentation\Grid;

use App\Shared\Grid\Columns;
use App\Shared\Presentation\Query\SortOrder;
use RuntimeException;

/**
 * View model for Grid/Table UI.
 * Orchestrates Column definitions, sorting state, and row data.
 * Validates row objects against column definitions.
 * @see \HyperfTest\Unit\Shared\Presentation\Grid\GridTest
 */
final readonly class Grid
{
    /** @var array<string, Column> Prepared renderable columns. */
    private array $columns;

    /**
     * @param array<int, object> $rows Data to display.
     * @throws RuntimeException If column field is missing in first row object.
     */
    public function __construct(
        private GridSort $sort,
        private array $rows
    ) {
        $fields = !empty($rows) ? get_object_vars($rows[0]) : [];
        $renderableColumns = [];

        foreach ($this->sort->columns()->columns() as $field => $colDef) {
            if (!empty($fields) && !array_key_exists($field, $fields)) {
                throw new RuntimeException("Column '{$field}' not found in row object");
            }

            $isCurrentlySorted = $field === $this->sortField();
            $ariaSort = 'none';
            $uri = '';

            if ($colDef->isSortable()) {
                if ($isCurrentlySorted) {
                    $ariaSort = $this->sortOrder()->isAscending() ? 'ascending' : 'descending';
                }

                $uri = $this->sort->uri($field, $isCurrentlySorted);
            }

            $renderableColumns[$field] = new Column($colDef, $uri, $ariaSort, $isCurrentlySorted);
        }

        $this->columns = $renderableColumns;
    }

    public function clientSideSort(): bool
    {
        return $this->sort->onClientSide();
    }

    public function sortField(): string
    {
        return $this->sort->sort()->field();
    }

    public function sortOrder(): SortOrder
    {
        return $this->sort->sort()->order();
    }

    public static function empty(): self
    {
        return new self(
            new GridSort('', [], new Columns()),
            []
        );
    }

    /**
     * @return array<string, Column>
     */
    public function columns(): array
    {
        return $this->columns;
    }

    public function rows(): array
    {
        return $this->rows;
    }

    public function columnCount(): int
    {
        return count($this->columns);
    }

    public function isEmpty(): bool
    {
        return empty($this->rows);
    }
}
