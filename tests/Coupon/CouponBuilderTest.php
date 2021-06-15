<?php

declare(strict_types=1);

namespace App\Tests\Coupon;

use App\Coupon\Constraint\LimitedLifetimeCouponConstraint;
use App\Coupon\Constraint\MinimumPurchaseAmountCouponConstraint;
use App\Coupon\CouponBuilder;
use App\Entity\Coupon\RateCoupon;
use App\Entity\Coupon\ValueCoupon;
use Money\Money;
use PHPUnit\Framework\TestCase;

final class CouponBuilderTest extends TestCase
{
    public function testCreateComplexValueCoupon(): void
    {
        $coupon = CouponBuilder::ofValue('A', 'EUR 10')
            ->requiresMinimumPurchaseAmountOf('EUR 100')
            ->usableBetween('2021-06-01', '2021-06-30')
            ->getCoupon();

        $this->assertEquals(
            new LimitedLifetimeCouponConstraint(
                new MinimumPurchaseAmountCouponConstraint(
                    new ValueCoupon('A', Money::EUR(1000)),
                    Money::EUR(10000)
                ),
                new \DateTimeImmutable('2021-06-01'),
                new \DateTimeImmutable('2021-06-30')
            ),
            $coupon
        );
    }

    public function testCreateComplexRateCoupon(): void
    {
        $coupon = CouponBuilder::ofPercentage('A', 20)
            ->requiresMinimumPurchaseAmountOf('EUR 100')
            ->usableBetween('2021-06-01', '2021-06-30')
            ->getCoupon();

        $this->assertEquals(
            new LimitedLifetimeCouponConstraint(
                new MinimumPurchaseAmountCouponConstraint(
                    new RateCoupon('A', .20),
                    Money::EUR(10000)
                ),
                new \DateTimeImmutable('2021-06-01'),
                new \DateTimeImmutable('2021-06-30')
            ),
            $coupon
        );
    }
}