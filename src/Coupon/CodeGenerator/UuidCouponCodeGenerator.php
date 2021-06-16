<?php

declare(strict_types=1);

namespace App\Coupon\CodeGenerator;

use Symfony\Component\Uid\Uuid;

final class UuidCouponCodeGenerator implements CouponCodeGeneratorInterface
{
    public function generate(array $options = []): string
    {
        return Uuid::v4()->toRfc4122();
    }
}