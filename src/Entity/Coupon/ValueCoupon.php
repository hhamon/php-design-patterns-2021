<?php

declare(strict_types=1);

namespace App\Entity\Coupon;

use App\Exception\IneligibleCouponException;
use Money\Money;

final class ValueCoupon implements CouponInterface
{
    private string $code;
    private Money $discount;

    public function __construct(string $code, Money $discount)
    {
        $this->code = $code;
        $this->discount = $discount;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function applyOn(Money $orderAmount): Money
    {
        if ($this->discount->greaterThan($orderAmount)) {
            throw new IneligibleCouponException('Order amount is lower than discount code');
        }

        return $orderAmount->subtract($this->discount);
    }
}