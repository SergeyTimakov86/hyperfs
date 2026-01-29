<?php

declare(strict_types=1);

namespace HyperfTest\Unit\Shared\Presentation\Query;

use App\Shared\Grid\Column;
use App\Shared\Grid\Columns;
use App\Shared\Infra\RequestParam;
use App\Shared\Presentation\Query\Sort;
use App\Shared\Presentation\Query\SortOrder;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(Sort::class)]
final class SortTest extends TestCase
{
    #[Test]
    public function emptyCreatesSortWithEmptyField(): void
    {
        $sort = Sort::empty();

        $this->assertSame('', $sort->field());
        $this->assertSame(SortOrder::DEFAULT, $sort->order());
    }

    #[Test]
    public function defaultCreatesSortWithSpecifiedField(): void
    {
        $field = 'created_at';
        $sort = Sort::default($field);

        $this->assertSame($field, $sort->field());
        $this->assertSame(SortOrder::DEFAULT, $sort->order());
    }

    #[Test]
    #[DataProvider('provideRequestData')]
    public function fromRequestQueryExtractsCorrectData(
        string $defaultSort,
        array $query,
        string $expectedField,
        SortOrder $expectedOrder
    ): void {
        $sort = Sort::fromRequestQuery($defaultSort, $query);

        $this->assertSame($expectedField, $sort->field());
        $this->assertSame($expectedOrder, $sort->order());
    }

    public static function provideRequestData(): array
    {
        return [
            'empty query uses defaults' => [
                'id',
                [],
                'id',
                SortOrder::ASC,
            ],
            'query with field' => [
                'id',
                [RequestParam::SORT_FIELD => 'name'],
                'name',
                SortOrder::ASC,
            ],
            'query with field and order' => [
                'id',
                [RequestParam::SORT_FIELD => 'name', RequestParam::SORT_ORDER => 'desc'],
                'name',
                SortOrder::DESC,
            ],
        ];
    }

    #[Test]
    public function fromRequestQueryRestrictsByColumns(): void
    {
        $columns = new Columns(
            new Column('id', 'ID', isSortable: true),
            new Column('name', 'Name', isSortable: false),
        );

        // Valid sortable field
        $sort = Sort::fromRequestQuery('id', [RequestParam::SORT_FIELD => 'id'], $columns);
        $this->assertSame('id', $sort->field());

        // Field exists but not sortable
        $sort = Sort::fromRequestQuery('id', [RequestParam::SORT_FIELD => 'name'], $columns);
        $this->assertSame('id', $sort->field());

        // Field does not exist
        $sort = Sort::fromRequestQuery('id', [RequestParam::SORT_FIELD => 'unknown'], $columns);
        $this->assertSame('id', $sort->field());
    }

    #[Test]
    public function fromRequestQueryUsesProvidedDefaultDirection(): void
    {
        $sort = Sort::fromRequestQuery('id', [], defaultDirection: SortOrder::DESC);

        $this->assertSame('id', $sort->field());
        $this->assertSame(SortOrder::DESC, $sort->order());
    }
}
