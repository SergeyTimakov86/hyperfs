<?php

declare(strict_types=1);

namespace HyperfTest\Unit\Domain\Model\Account;

use App\Domain\Model\Account\IsSeller;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class IsSellerTest extends TestCase
{
    #[Test]
    public function isSellerValue(): void
    {
        $true = IsSeller::of(true);
        $this->assertTrue($true->value());
        $this->assertEquals('+', (string) $true);

        $false = IsSeller::of(false);
        $this->assertFalse($false->value());
        $this->assertEquals('-', (string) $false);
    }
}
