<?php

declare(strict_types=1);

namespace App\Entity;

use App\Collection\ProductCollection;
use Assert\Assertion;
use Money\Money;

class ComboProduct implements ProductInterface
{
    private string $name;
    private ?Money $retailPrice = null;
    private ProductCollection $products;

    /**
     * @param ProductInterface[] $products
     */
    public function __construct(string $name, array $products, ?Money $retailPrice = null)
    {
        Assertion::minCount($products, 2, 'Combo must combine at least 2 products');

        $this->name = $name;
        $this->retailPrice = $retailPrice;
        $this->products = new ProductCollection(...\array_values($products));
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getRetailPrice(): Money
    {
        if ($this->retailPrice) {
            return $this->retailPrice;
        }

        return $this->products->getRetailPrice();
    }
}