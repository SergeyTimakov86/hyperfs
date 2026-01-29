<?php

declare(strict_types=1);

namespace HyperfTest\Unit\Shared\Grid;

use App\Shared\Grid\Column;
use App\Shared\Grid\Columns;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use RuntimeException;

#[CoversClass(Columns::class)]
final class ColumnsTest extends TestCase
{
    #[Test]
    public function itThrowsExceptionOnDuplicateField(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage("Column 'id' already exists");

        new Columns(
            new Column('id', 'ID'),
            new Column('id', 'Duplicate ID')
        );
    }

    #[Test]
    public function itReturnsAllColumns(): void
    {
        $columns = new Columns(
            $c1 = new Column('id', 'ID'),
            $c2 = new Column('name', 'Name')
        );

        $this->assertCount(2, $columns->columns());
        $this->assertSame($c1, $columns->columns()['id']);
        $this->assertSame($c2, $columns->columns()['name']);
    }

    #[Test]
    public function itReturnsSortableFields(): void
    {
        $columns = new Columns(
            new Column('id', 'ID', isSortable: true),
            new Column('name', 'Name', isSortable: false),
            new Column('age', 'Age', isSortable: true)
        );

        $sortable = $columns->sortableFields();
        $this->assertCount(2, $sortable);
        $this->assertArrayHasKey('id', $sortable);
        $this->assertArrayHasKey('age', $sortable);
        $this->assertArrayNotHasKey('name', $sortable);
    }
}
