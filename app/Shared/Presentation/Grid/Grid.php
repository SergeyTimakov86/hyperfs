<?php

declare(strict_types=1);

namespace App\Shared\Presentation\Grid;

use App\Shared\Presentation\Query\Sort;
use App\Shared\Presentation\Query\SortOrder;
use App\Shared\RequestParam;
use RuntimeException;

final readonly class Grid
{
    /**
     * @param array<int, object> $rows
     */
    public function __construct(
        private Columns $columns,
        private array $rows,
        private Sort $sort,
        private string $uriQuery
    ) {
        if (!$this->isEmpty()) {
            $fields = get_object_vars($rows[0]);

            $newUriQuery = '?' . $uriQuery;

            if (!empty($this->columns->sortableFields())) {
                parse_str($uriQuery, $queryData);
                unset($queryData[RequestParam::SORT_FIELD], $queryData[RequestParam::SORT_ORDER]);
                $newUriQuery = http_build_query($queryData);
                if ($newUriQuery !== '') {
                    $newUriQuery .= '&';
                }
                $newUriQuery = '?' . $newUriQuery . RequestParam::SORT_FIELD . '=';
            }

            foreach ($this->columns->columns() as $field => $column) {
                if (!isset($fields[$column->field()])) {
                    throw new RuntimeException("Column '{$column->field()}' not found in row object");
                }

                if ($column->isSortable()) {
                    if ($field === $this->sortField()) {
                        $column->currentlySorted();
                        $column->asAriaSort($this->sortOrder()->isAscending() ? 'ascending' : 'descending');
                    }

                    $column->asUriQuery(
                        $newUriQuery . $field . (
                            $column->sorted()
                                ? '&' . RequestParam::SORT_ORDER . '=' . $this->sortOrder()->opposite()->value
                                : ''
                        )
                    );
                }
            }
        }
    }

    public function sortField(): string
    {
        return $this->sort->field();
    }

    public function sortOrder(): SortOrder
    {
        return $this->sort->order();
    }

    public static function empty(): self
    {
        return new self(new Columns(), [], Sort::empty(), '');
    }

    /**
     * @return array<string, Column>
     */
    public function columns(): array
    {
        return $this->columns->columns();
    }

    public function rows(): array
    {
        return $this->rows;
    }

    public function isEmpty(): bool
    {
        return empty($this->rows);
    }
}
