<?php

declare(strict_types=1);

namespace App\Entity;

use Assert\Assertion;
use Money\Money;

class ComboProduct implements ProductInterface
{
    private string $name;
    private ?Money $retailPrice = null;

    /** @var ProductInterface[] */
    private array $products = [];

    /**
     * @param ProductInterface[] $products
     */
    public function __construct(string $name, array $products, ?Money $retailPrice = null)
    {
        Assertion::allIsInstanceOf($products, ProductInterface::class, 'Combo must combine ProductInterface instances.');
        Assertion::minCount($products, 2, 'Combo must combine at least 2 products');

        $this->name = $name;
        $this->retailPrice = $retailPrice;
        $this->products = \array_values($products);
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

        $retailPrice = $this->products[0]->getRetailPrice();

        $nb = \count($this->products);
        for ($i = 1; $i < $nb; ++$i) {
            $retailPrice = $retailPrice->add($this->products[$i]->getRetailPrice());
        }

        return $retailPrice;
    }
}