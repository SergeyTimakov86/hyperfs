<?php

declare(strict_types=1);

namespace App\Shared\Grid;

/**
 * Supported Grid column data types.
 * @see \HyperfTest\Unit\Shared\Grid\ColumnTest
 */
enum ColumnType: string
{
    case STRING = 'string';
    case NUMERIC = 'numeric';
    case DATE = 'date';
}
