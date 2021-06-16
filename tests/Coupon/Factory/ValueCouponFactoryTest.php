<?php

declare(strict_types=1);

namespace App\Tests\Coupon\Factory;

use App\Coupon\CodeGenerator\FixedUuidCouponCodeGenerator;
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

        $this->factory = new ValueCouponFactory(new FixedUuidCouponCodeGenerator());
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
            new ValueCoupon('4856cf24-49b8-4f4a-b758-417104f42f94', Money::EUR(2000)),
            $coupon
        );
    }
}