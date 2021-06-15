<?php

declare(strict_types=1);

namespace App\Entity\Coupon;

use App\Exception\IneligibleCouponException;
use Money\Money;

interface CouponInterface
{
    public function getCode(): string;

    /**
     * @throws IneligibleCouponException
     */
    public function applyOn(Money $orderAmount): Money;
}