<?php

declare(strict_types=1);

namespace App\Shared\Presentation\Query;

use App\Shared\Presentation\Grid\Columns;
use App\Shared\RequestParam;
use Hyperf\HttpServer\Contract\RequestInterface;

final readonly class Sort
{
    private function __construct(
        private string $field,
        private SortOrder $order,
    ) {
    }

    public static function empty(): self
    {
        return new self('', SortOrder::DEFAULT);
    }

    public static function fromRequest(
        RequestInterface $request,
        string $defaultSort,
        ?Columns $restrictByGridColumns = null,
        SortOrder $defaultDirection = SortOrder::ASC,
    ): self {
        $sortField = $request->query(RequestParam::SORT_FIELD, $defaultSort);

        if ($restrictByGridColumns && !isset($restrictByGridColumns->sortableFields()[$sortField])) {
            $sortField = $defaultSort;
        }

        return new self(
            $sortField,
            SortOrder::fromRequest($request, $defaultDirection),
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
