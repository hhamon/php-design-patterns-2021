<?php

declare(strict_types=1);

namespace App\Entity;

use Money\Money;

class PhysicalProduct implements ProductInterface
{
    private string $name;
    private Money $retailPrice;

    public function __construct(string $name, Money $retailPrice)
    {
        $this->name = $name;
        $this->retailPrice = $retailPrice;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getRetailPrice(): Money
    {
        return $this->retailPrice;
    }
}