<?php

declare(strict_types=1);

namespace HyperfTest\Unit\Shared\Presentation\Grid;

use App\Shared\Grid\Column as ColumnDefinition;
use App\Shared\Grid\ColumnType;
use App\Shared\Presentation\Grid\Column;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class ColumnTest extends TestCase
{
    #[Test]
    public function itProxiesDefinitionMethods(): void
    {
        $def = new ColumnDefinition('id', 'ID', true, ColumnType::NUMERIC);
        $column = new Column($def, '/url', 'ascending', true);

        $this->assertEquals('id', $column->field());
        $this->assertEquals('ID', $column->label());
        $this->assertEquals(ColumnType::NUMERIC, $column->type());
        $this->assertTrue($column->isSortable());
        $this->assertEquals('/url', $column->uriQuery());
        $this->assertEquals('ascending', $column->ariaSort());
        $this->assertTrue($column->sorted());
    }

    #[Test]
    public function itHasDefaults(): void
    {
        $def = new ColumnDefinition('name', 'Name');
        $column = new Column($def);

        $this->assertEquals('', $column->uriQuery());
        $this->assertEquals('none', $column->ariaSort());
        $this->assertFalse($column->sorted());
    }
}
