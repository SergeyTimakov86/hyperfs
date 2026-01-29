<?php

declare(strict_types=1);

namespace HyperfTest\Unit\Shared\Grid;

use App\Shared\Grid\Column;
use App\Shared\Grid\ColumnType;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(Column::class)]
#[CoversClass(ColumnType::class)]
final class ColumnTest extends TestCase
{
    #[Test]
    public function itHasCorrectProperties(): void
    {
        $column = new Column('id', 'ID', true, ColumnType::NUMERIC);

        $this->assertEquals('id', $column->field());
        $this->assertEquals('ID', $column->label());
        $this->assertTrue($column->isSortable());
        $this->assertEquals(ColumnType::NUMERIC, $column->type());
    }

    #[Test]
    public function itDefaultsToStringTypeAndNotSortable(): void
    {
        $column = new Column('name', 'Name');

        $this->assertEquals(ColumnType::STRING, $column->type());
        $this->assertFalse($column->isSortable());
    }
}
