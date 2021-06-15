<?php

declare(strict_types=1);

namespace App\Coupon\Factory;

use App\Entity\Coupon\CouponInterface;

abstract class AbstractCouponFactory implements CouponFactoryInterface
{
    public function createCoupon(array $options): CouponInterface
    {
        $code = $options['code'] ?? $this->generateCouponCode();

        if ($options['code'] ?? null) {
            unset($options['code']);
        }

        return $this->issueCoupon($code, $options);
    }

    abstract protected function issueCoupon(string $code, array $options): CouponInterface;

    private function generateCouponCode(): string
    {
        return 'SYMFONY_COUPON';
    }
}