<?php

declare(strict_types=1);

namespace App\Tests\Entity;

use App\Entity\PhysicalProduct;
use Money\Money;
use PHPUnit\Framework\TestCase;

final class PhysicalProductTest extends TestCase
{
    public function testCreatePhysicalProduct(): void
    {
        $product = new PhysicalProduct('A', Money::EUR(1500));

        $this->assertSame('A', $product->getName());
        $this->assertEquals(Money::EUR(1500), $product->getRetailPrice());
    }
}