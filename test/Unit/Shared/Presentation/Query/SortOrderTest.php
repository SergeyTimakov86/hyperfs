<?php

declare(strict_types=1);

namespace HyperfTest\Unit\Shared\Presentation\Query;

use App\Shared\Infra\RequestParam;
use App\Shared\Presentation\Query\SortOrder;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(SortOrder::class)]
final class SortOrderTest extends TestCase
{
    #[Test]
    public function identifiesAscending(): void
    {
        $this->assertTrue(SortOrder::ASC->isAscending());
        $this->assertFalse(SortOrder::DESC->isAscending());
    }

    #[Test]
    public function returnsOpposite(): void
    {
        $this->assertSame(SortOrder::DESC, SortOrder::ASC->opposite());
        $this->assertSame(SortOrder::ASC, SortOrder::DESC->opposite());
    }

    #[Test]
    #[DataProvider('provideRequestData')]
    public function fromRequestQueryExtractsCorrectData(
        array $query,
        SortOrder $expected,
        SortOrder $default = SortOrder::DEFAULT
    ): void {
        $this->assertSame($expected, SortOrder::fromRequestQuery($query, $default));
    }

    public static function provideRequestData(): array
    {
        return [
            'empty query uses default asc' => [
                [],
                SortOrder::ASC,
            ],
            'explicit asc' => [
                [RequestParam::SORT_ORDER => 'asc'],
                SortOrder::ASC,
            ],
            'explicit desc' => [
                [RequestParam::SORT_ORDER => 'desc'],
                SortOrder::DESC,
            ],
            'invalid value uses default' => [
                [RequestParam::SORT_ORDER => 'invalid'],
                SortOrder::ASC,
            ],
            'custom default' => [
                [],
                SortOrder::DESC,
                SortOrder::DESC,
            ],
            'invalid value with custom default' => [
                [RequestParam::SORT_ORDER => 'asc'],
                SortOrder::ASC,
                SortOrder::DESC,
            ],
        ];
    }
}
