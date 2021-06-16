<?php

declare(strict_types=1);

namespace App\Coupon\Factory;

use App\Coupon\CodeGenerator\CouponCodeGeneratorInterface;
use App\Entity\Coupon\CouponInterface;

abstract class AbstractCouponFactory implements CouponFactoryInterface
{
    private CouponCodeGeneratorInterface $generator;

    /**
     * The $generator argument is a "Strategy" pattern implementation.
     */
    public function __construct(CouponCodeGeneratorInterface $generator)
    {
        $this->generator = $generator;
    }

    /**
     * This method is a "Template Method" pattern implementation.
     */
    final public function createCoupon(array $options): CouponInterface
    {
        $code = $options['code'] ?? $this->generator->generate($options);

        if ($options['code'] ?? null) {
            unset($options['code']);
        }

        return $this->issueCoupon($code, $options);
    }

    /**
     * This method is a "Factory Method" pattern implementation.
     */
    abstract protected function issueCoupon(string $code, array $options): CouponInterface;
}