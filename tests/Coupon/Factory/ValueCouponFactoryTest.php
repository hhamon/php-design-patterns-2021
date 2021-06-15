<?php

declare(strict_types=1);

namespace App\Tests\Coupon\Factory;

use App\Coupon\Factory\CouponFactoryInterface;
use App\Coupon\Factory\ValueCouponFactory;
use App\Entity\Coupon\ValueCoupon;
use Money\Money;
use PHPUnit\Framework\TestCase;

final class ValueCouponFactoryTest extends TestCase
{
    private CouponFactoryInterface $factory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->factory = new ValueCouponFactory();
    }

    public function testCreateValueCouponWithFixedCouponCode(): void
    {
        $coupon = $this->factory->createCoupon([
            'discount' => 'EUR 10',
            'code' => 'CUSTOM',
        ]);

        $this->assertEquals(
            new ValueCoupon('CUSTOM', Money::EUR(1000)),
            $coupon
        );
    }

    public function testCreateValueCouponWithGeneratedCouponCode(): void
    {
        $coupon = $this->factory->createCoupon(['discount' => 'EUR 20']);

        $this->assertEquals(
            new ValueCoupon('SYMFONY_COUPON', Money::EUR(2000)),
            $coupon
        );
    }
}