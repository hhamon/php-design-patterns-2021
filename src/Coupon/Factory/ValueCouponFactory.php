<?php

declare(strict_types=1);

namespace App\Coupon\Factory;

use App\Coupon\CodeGenerator\CouponCodeGeneratorInterface;
use App\Entity\Coupon\ValueCoupon;
use App\Money\MoneyRegistry;
use Assert\Assertion;

final class ValueCouponFactory extends AbstractCouponFactory
{
    private MoneyRegistry $registry;

    public function __construct(CouponCodeGeneratorInterface $generator)
    {
        parent::__construct($generator);

        $this->registry = new MoneyRegistry();
    }

    protected function issueCoupon(string $code, array $options): ValueCoupon
    {
        Assertion::notEmpty($options['discount'] ?? '', 'Value coupon discount is required.');

        return new ValueCoupon($code, $this->registry->createMoney($options['discount']));
    }
}