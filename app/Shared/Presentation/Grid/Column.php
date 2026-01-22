<?php

declare(strict_types=1);

namespace App\Shared\Presentation\Grid;

final class Column
{
    private bool $isEditable = false;
    private bool $isSortable = false;
    private bool $currentlySorted = false;
    private string $uriQuery = '';
    private string $ariaSort = 'none';

    public function __construct(
        private readonly string $field,
        private readonly string $label,
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

    public function isEditable(): bool
    {
        return $this->isEditable;
    }

    public function isSortable(): bool
    {
        return $this->isSortable;
    }

    public function sorted(): bool
    {
        return $this->currentlySorted;
    }

    public function editable(): self
    {
        $this->isEditable = true;
        return $this;
    }

    public function sortable(): self
    {
        $this->isSortable = true;
        return $this;
    }

    public function currentlySorted(): self
    {
        $this->currentlySorted = true;
        return $this;
    }

    public function uriQuery(): string
    {
        return $this->uriQuery;
    }

    public function asUriQuery(string $uriQuery): self
    {
        $this->uriQuery = $uriQuery;
        return $this;
    }

    public function ariaSort(): string
    {
        return $this->ariaSort;
    }

    public function asAriaSort(string $ariaSort): self
    {
        $this->ariaSort = $ariaSort;
        return $this;
    }
}
