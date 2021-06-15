<?php

declare(strict_types=1);

namespace App\Collection;

use App\Entity\ProductInterface;
use Money\Money;

final class ProductCollection implements \Countable
{
    /** @var ProductInterface[] */
    private array $items = [];

    public function __construct(ProductInterface ...$items)
    {
        $this->items = $items;
    }

    public function add(ProductInterface $product): void
    {
        if (! \in_array($product, $this->items, true)) {
            $this->items[] = $product;
        }
    }

    public function count(): int
    {
        return \count($this->items);
    }

    public function toArray(): array
    {
        return $this->items;
    }

    public function getRetailPrice(): Money
    {
        $retailPrice = $this->items[0]->getRetailPrice();

        $nb = \count($this->items);
        for ($i = 1; $i < $nb; ++$i) {
            $retailPrice = $retailPrice->add($this->items[$i]->getRetailPrice());
        }

        return $retailPrice;
    }
}