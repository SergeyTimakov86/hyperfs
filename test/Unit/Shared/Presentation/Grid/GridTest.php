<?php

declare(strict_types=1);

namespace HyperfTest\Unit\Shared\Presentation\Grid;

use App\Shared\Grid\Column as ColumnDef;
use App\Shared\Grid\Columns;
use App\Shared\Infra\RequestParam;
use App\Shared\Presentation\Grid\Grid;
use App\Shared\Presentation\Grid\GridSort;
use App\Shared\Presentation\Query\SortOrder;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use RuntimeException;

#[CoversClass(Grid::class)]
final class GridTest extends TestCase
{
    #[Test]
    public function emptyCreatesEmptyGrid(): void
    {
        $grid = Grid::empty();

        $this->assertTrue($grid->isEmpty());
        $this->assertSame(0, $grid->columnCount());
        $this->assertSame([], $grid->rows());
        $this->assertSame([], $grid->columns());
    }

    #[Test]
    public function itCalculatesColumnsAndState(): void
    {
        $columns = new Columns(
            new ColumnDef('id', 'ID', isSortable: true),
            new ColumnDef('name', 'Name', isSortable: false),
        );

        $rows = [
            (object)['id' => 1, 'name' => 'John'],
            (object)['id' => 2, 'name' => 'Jane'],
        ];

        // Server-side sort, currently sorted by 'id' ASC
        $gridSort = new GridSort(
            'id',
            [RequestParam::SORT_FIELD => 'id', RequestParam::SORT_ORDER => 'asc'],
            $columns,
            onClientSide: false
        );

        $grid = new Grid($gridSort, $rows);

        $this->assertFalse($grid->isEmpty());
        $this->assertSame(2, $grid->columnCount());
        $this->assertSame($rows, $grid->rows());
        $this->assertSame('id', $grid->sortField());
        $this->assertSame(SortOrder::ASC, $grid->sortOrder());
        $this->assertFalse($grid->clientSideSort());

        $renderableColumns = $grid->columns();
        $this->assertArrayHasKey('id', $renderableColumns);
        $this->assertArrayHasKey('name', $renderableColumns);

        $idCol = $renderableColumns['id'];
        $this->assertSame('ascending', $idCol->ariaSort());
        $this->assertTrue($idCol->sorted());
        // opposite of ASC is DESC
        $this->assertStringContainsString('dir=desc', $idCol->uriQuery());

        $nameCol = $renderableColumns['name'];
        $this->assertSame('none', $nameCol->ariaSort());
        $this->assertFalse($nameCol->sorted());
        $this->assertSame('', $nameCol->uriQuery());
    }

    #[Test]
    public function itThrowsExceptionIfFieldMissingInRow(): void
    {
        $columns = new Columns(
            new ColumnDef('missing_field', 'Missing'),
        );

        $rows = [
            (object)['id' => 1],
        ];

        $gridSort = new GridSort('id', [], $columns);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage("Column 'missing_field' not found in row object");

        new Grid($gridSort, $rows);
    }

    #[Test]
    public function itHandlesEmptyRowsWithColumns(): void
    {
        $columns = new Columns(
            new ColumnDef('id', 'ID'),
        );

        $gridSort = new GridSort('id', [], $columns);
        $grid = new Grid($gridSort, []);

        $this->assertTrue($grid->isEmpty());
        $this->assertSame(1, $grid->columnCount());
        $this->assertArrayHasKey('id', $grid->columns());
    }

    #[Test]
    public function itCorrectlyIdentifiesDescendingOrder(): void
    {
        $columns = new Columns(
            new ColumnDef('id', 'ID', isSortable: true),
        );

        $gridSort = new GridSort(
            'id',
            [RequestParam::SORT_FIELD => 'id', RequestParam::SORT_ORDER => 'desc'],
            $columns,
            onClientSide: false
        );

        $grid = new Grid($gridSort, [(object)['id' => 1]]);

        $this->assertSame(SortOrder::DESC, $grid->sortOrder());
        $this->assertSame('descending', $grid->columns()['id']->ariaSort());
    }
}
