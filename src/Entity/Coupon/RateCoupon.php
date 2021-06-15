<?php

declare(strict_types=1);

namespace App\Entity\Coupon;

use Assert\Assertion;
use Money\Money;

final class RateCoupon implements CouponInterface
{
    private string $code;
    private float $rate;

    public static function fromPercentage(string $code, int $percentage): self
    {
        Assertion::greaterThan($percentage, 0);
        Assertion::lessOrEqualThan($percentage, 100);

        return new self($code, $percentage / 100);
    }

    public function __construct(string $code, float $rate)
    {
        Assertion::greaterThan($rate, 0);
        Assertion::lessOrEqualThan($rate, 1);

        $this->code = $code;
        $this->rate = $rate;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function applyOn(Money $orderAmount): Money
    {
        return $orderAmount->multiply(1 - $this->rate);
    }
}