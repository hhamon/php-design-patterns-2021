<?php

declare(strict_types=1);

namespace App\Entity\Coupon;

use App\Coupon\CouponBuilder;

class CouponService
{
    public function createCoupon(CouponDefinition $couponDefinition): CouponInterface
    {
        /*switch ($couponDefinition->getCouponType()) {
            case 'RATE':
                $couponBuilder = CouponBuilder::ofRate($couponDefinition->getCode(), $couponDefinition->getRate());
                break;
            case 'VALUE':
                break;
        }

        if ($couponDefinition->requiresMinAmount) {
            $couponBuilder->requiresMinimumPurchaseAmountOf(...);
        }

        return $couponBuilder->getCoupon();
        */
    }
}