<?php

declare(strict_types=1);

namespace App\Tests\Coupon\Factory;

use App\Coupon\CodeGenerator\CouponCodeGeneratorInterface;
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

        $stub = $this->createStub(CouponCodeGeneratorInterface::class);
        $stub->method('generate')->willReturn('4856cf24-49b8-4f4a-b758-417104f42f94');

        /*$mock = $this->createMock(CouponCodeGeneratorInterface::class);
        $mock->expects($this->once())
            ->method('generate')
            ->with(['discount' => 'EUR 10', 'code' => 'CUSTOM'])
            ->willReturn('4856cf24-49b8-4f4a-b758-417104f42f94');*/

        $this->factory = new ValueCouponFactory($stub);
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