<?php

declare(strict_types=1);

namespace App\Coupon\Constraint;

use App\Entity\Coupon\CouponInterface;

abstract class AbstractCouponConstraint implements CouponInterface
{
    protected CouponInterface $coupon;

    public function __construct(CouponInterface $coupon)
    {
        $this->coupon = $coupon;
    }

    public function getCode(): string
    {
        return $this->coupon->getCode();
    }
}