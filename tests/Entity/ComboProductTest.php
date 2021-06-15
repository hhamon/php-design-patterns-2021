<?php

declare(strict_types=1);

namespace App\Tests\Entity;

use App\Entity\ComboProduct;
use App\Entity\PhysicalProduct;
use Money\Money;
use PHPUnit\Framework\TestCase;

final class ComboProductTest extends TestCase
{
    public function testComboProductWithFixedPrice(): void
    {
        $products[] = new PhysicalProduct('A', Money::EUR(1500));
        $products[] = new PhysicalProduct('B', Money::EUR(3000));

        $product = new ComboProduct('C', $products, Money::EUR(4000));

        $this->assertSame('C', $product->getName());
        $this->assertEquals(Money::EUR(4000), $product->getRetailPrice());
    }

    public function testComboProductWithoutFixedPrice(): void
    {
        $products[] = new PhysicalProduct('A', Money::EUR(1500));
        $products[] = new PhysicalProduct('B', Money::EUR(3000));

        $product = new ComboProduct('C', $products);

        $this->assertSame('C', $product->getName());
        $this->assertEquals(Money::EUR(4500), $product->getRetailPrice());
    }

    public function testSuperComboProductWithoutFixedPrice(): void
    {
        $comboProducts[] = new PhysicalProduct('A', Money::EUR(1500));
        $comboProducts[] = new PhysicalProduct('B', Money::EUR(3000));

        $products[] = new ComboProduct('C', $comboProducts);
        $products[] = new PhysicalProduct('D', Money::EUR(1000));

        $product = new ComboProduct('E', $products);

        $this->assertSame('E', $product->getName());
        $this->assertEquals(Money::EUR(5500), $product->getRetailPrice());
    }
}