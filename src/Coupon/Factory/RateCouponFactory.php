<?php

declare(strict_types=1);

namespace App\Coupon\Factory;

use App\Entity\Coupon\RateCoupon;

final class RateCouponFactory extends AbstractCouponFactory
{
    protected function issueCoupon(string $code, array $options): RateCoupon
    {
        $rate = $options['rate'] ?? null;
        $percentage = $options['percentage'] ?? null;

        if (! $rate && ! $percentage) {
            throw new \RuntimeException('Either "rate" or "percentage" option must be given.');
        }

        if ($rate) {
            return new RateCoupon($code, $rate);
        }

        return RateCoupon::fromPercentage($code, $percentage);
    }
}