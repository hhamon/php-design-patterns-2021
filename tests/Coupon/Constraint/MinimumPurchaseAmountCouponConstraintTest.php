<?php

declare(strict_types=1);

namespace App\Tests\Coupon\Constraint;

use App\Coupon\Constraint\MinimumPurchaseAmountCouponConstraint;
use App\Entity\Coupon\CouponInterface;
use App\Entity\Coupon\RateCoupon;
use App\Exception\IneligibleCouponException;
use Money\Money;
use PHPUnit\Framework\TestCase;

final class MinimumPurchaseAmountCouponConstraintTest extends TestCase
{
    private CouponInterface $coupon;

    protected function setUp(): void
    {
        parent::setUp();

        $this->coupon = RateCoupon::fromPercentage('A', 20);
    }

    public function testMinimumAmountIsNotReached(): void
    {
        $coupon = $this->createCoupon();

        $this->expectException(IneligibleCouponException::class);
        $this->expectExceptionMessage('Order amount must be greater or equal than minimum amount of EUR 10000');

        $coupon->applyOn(Money::EUR(9999));
    }

    public function testCouponIsApplied(): void
    {
        $coupon = $this->createCoupon();

        $this->assertEquals(Money::EUR(8000), $coupon->applyOn(Money::EUR(10000)));
    }

    private function createCoupon(): CouponInterface
    {
        return new MinimumPurchaseAmountCouponConstraint(
            $this->coupon,
            Money::EUR(10000)
        );
    }
}