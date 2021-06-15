<?php

declare(strict_types=1);

namespace App\Coupon\Factory;

use App\Entity\Coupon\CouponInterface;

interface CouponFactoryInterface
{
    public function createCoupon(array $options): CouponInterface;
}