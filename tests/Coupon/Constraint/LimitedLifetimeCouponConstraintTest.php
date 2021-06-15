<?php

declare(strict_types=1);

namespace App\Tests\Coupon\Constraint;

use App\Coupon\Constraint\LimitedLifetimeCouponConstraint;
use App\Entity\Coupon\CouponInterface;
use App\Entity\Coupon\ValueCoupon;
use App\Exception\IneligibleCouponException;
use Money\Money;
use PHPUnit\Framework\TestCase;
use Symfony\Bridge\PhpUnit\ClockMock;

final class LimitedLifetimeCouponConstraintTest extends TestCase
{
    private CouponInterface $coupon;

    protected function setUp(): void
    {
        parent::setUp();

        ClockMock::register(LimitedLifetimeCouponConstraint::class);

        $this->coupon = new ValueCoupon('A', Money::EUR(500));
    }

    public function testCouponIsExpired(): void
    {
        ClockMock::withClockMock(\strtotime('yesterday'));

        $coupon = $this->createCoupon();

        $this->expectException(IneligibleCouponException::class);
        $this->expectExceptionMessage('Coupon is expired');

        $coupon->applyOn(Money::EUR(20000));
    }

    public function testCouponIsNotYetEligible(): void
    {
        ClockMock::withClockMock(\strtotime('2021-05-31 15:31:12'));

        $coupon = $this->createCoupon();

        $this->expectException(IneligibleCouponException::class);
        $this->expectExceptionMessage('Coupon is not yet eligible');

        $coupon->applyOn(Money::EUR(20000));
    }

    public function testCouponIsApplied(): void
    {
        ClockMock::withClockMock(\strtotime('2021-06-02 15:31:12'));

        $coupon = $this->createCoupon();

        $this->assertEquals(Money::EUR(19500), $coupon->applyOn(Money::EUR(20000)));
    }

    private function createCoupon(): CouponInterface
    {
        return LimitedLifetimeCouponConstraint::between(
            $this->coupon,
            '2021-06-01 00:00:00',
            '2021-06-10 23:59:59'
        );
    }
}