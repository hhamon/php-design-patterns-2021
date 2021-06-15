<?php

declare(strict_types=1);

namespace App\Coupon\Constraint;

use App\Entity\Coupon\CouponInterface;
use App\Exception\IneligibleCouponException;
use Assert\Assertion;
use Money\Money;

final class MinimumPurchaseAmountCouponConstraint extends AbstractCouponConstraint
{
    private Money $minimumAmount;

    public function __construct(CouponInterface $coupon, Money $minimumAmount)
    {
        Assertion::true($minimumAmount->isPositive(), 'Minimum amount must be positive');

        parent::__construct($coupon);

        $this->minimumAmount = $minimumAmount;
    }

    public function applyOn(Money $orderAmount): Money
    {
        if ($orderAmount->lessThan($this->minimumAmount)) {
            throw new IneligibleCouponException(\sprintf('Order amount must be greater or equal than minimum amount of %s %s', $this->minimumAmount->getCurrency()->getCode(), $this->minimumAmount->getAmount()));
        }

        return $this->coupon->applyOn($orderAmount);
    }
}