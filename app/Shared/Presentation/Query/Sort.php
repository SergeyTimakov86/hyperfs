<?php

declare(strict_types=1);

namespace App\Shared\Presentation\Query;

use App\Shared\Grid\Columns;
use App\Shared\Infra\RequestParam;

/**
 * Handles UI sorting state. Immutable.
 * Links request params to grid-validated fields.
 * @see \HyperfTest\Unit\Shared\Presentation\Query\SortTest
 */
final readonly class Sort
{
    public function __construct(
        private string $field,
        private SortOrder $order,
    ) {
    }

    /** No field, default order. */
    public static function empty(): self
    {
        return self::default('');
    }

    /** Specific field, default order. */
    public static function default(string $defaultParam): self
    {
        return new self($defaultParam, SortOrder::DEFAULT);
    }

    /**
     * Factory from query array.
     * 1. Extracts field via RequestParam::SORT_FIELD ('sort').
     * 2. If $restrictByGridColumns provided, resets field to $defaultSort if not in sortable list.
     * 3. Delegates order extraction to SortOrder::fromRequestQuery (uses 'dir').
     */
    public static function fromRequestQuery(
        string $defaultSort,
        array $requestQuery,
        ?Columns $restrictByGridColumns = null,
        SortOrder $defaultDirection = SortOrder::DEFAULT,
    ): self {
        $sortField = $requestQuery[RequestParam::SORT_FIELD] ?? $defaultSort;

        if ($restrictByGridColumns && !isset($restrictByGridColumns->sortableFields()[$sortField])) {
            $sortField = $defaultSort;
        }

        return new self(
            $sortField,
            SortOrder::fromRequestQuery($requestQuery, $defaultDirection),
        );
    }

    public function field(): string
    {
        return $this->field;
    }

    public function order(): SortOrder
    {
        return $this->order;
    }
}
