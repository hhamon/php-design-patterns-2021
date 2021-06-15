<?php

declare(strict_types=1);

namespace App\Coupon\Constraint;

use App\Entity\Coupon\CouponInterface;
use App\Exception\IneligibleCouponException;
use Money\Money;

final class LimitedLifetimeCouponConstraint extends AbstractCouponConstraint
{
    private \DateTimeImmutable $from;
    private \DateTimeImmutable $until;

    public static function between(CouponInterface $coupon, string $from, string $until): self
    {
        return new self($coupon, new \DateTimeImmutable($from), new \DateTimeImmutable($until));
    }

    public function __construct(CouponInterface $coupon, \DateTimeImmutable $from, \DateTimeImmutable $until)
    {
        if ($from > $until) {
            throw new \InvalidArgumentException('Invalid date period');
        }

        parent::__construct($coupon);

        $this->from = $from;
        $this->until = $until;
    }

    public function applyOn(Money $orderAmount): Money
    {
        if (time() < $this->from->getTimestamp()) {
            throw new IneligibleCouponException('Coupon is not yet eligible');
        }

        if (time() > $this->until->getTimestamp()) {
            throw new IneligibleCouponException('Coupon is expired');
        }

        return $this->coupon->applyOn($orderAmount);
    }
}