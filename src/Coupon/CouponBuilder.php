<?php

declare(strict_types=1);

namespace App\Coupon;

use App\Coupon\Constraint\LimitedLifetimeCouponConstraint;
use App\Coupon\Constraint\MinimumPurchaseAmountCouponConstraint;
use App\Entity\Coupon\CouponInterface;
use App\Entity\Coupon\RateCoupon;
use App\Entity\Coupon\ValueCoupon;
use Money\Currencies\ISOCurrencies;
use Money\Currency;
use Money\Parser\DecimalMoneyParser;

class CouponBuilder
{
    private CouponInterface $coupon;

    public static function ofValue(string $code, string $discount): self
    {
        [$currency, $amount] = \explode(' ', $discount);

        $parser = new DecimalMoneyParser(new ISOCurrencies());

        return new self(new ValueCoupon($code, $parser->parse($amount, new Currency($currency))));
    }

    public static function ofRate(string $code, float $rate): self
    {
        return new self(new RateCoupon($code, $rate));
    }

    public static function ofPercentage(string $code, int $percentage): self
    {
        return new self(RateCoupon::fromPercentage($code, $percentage));
    }

    public function requiresMinimumPurchaseAmountOf(string $discount): self
    {
        [$currency, $amount] = \explode(' ', $discount);

        $parser = new DecimalMoneyParser(new ISOCurrencies());

        $this->coupon = new MinimumPurchaseAmountCouponConstraint($this->coupon, $parser->parse($amount, new Currency($currency)));

        return $this;
    }

    public function usableBetween(string $from, string $until): self
    {
        $this->coupon = LimitedLifetimeCouponConstraint::between($this->coupon, $from, $until);

        return $this;
    }

    public function onlyOnFirstOrder(): self
    {
        return $this;
    }

    public function getCoupon(): CouponInterface
    {
        return $this->coupon;
    }

    private function __construct(CouponInterface $coupon)
    {
        $this->coupon = $coupon;
    }
}