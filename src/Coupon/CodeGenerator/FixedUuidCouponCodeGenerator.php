<?php

declare(strict_types=1);

namespace App\Coupon\CodeGenerator;

/**
 * Only meant for testing purpose
 */
final class FixedUuidCouponCodeGenerator implements CouponCodeGeneratorInterface
{
    public function generate(array $options = []): string
    {
        return '4856cf24-49b8-4f4a-b758-417104f42f94';
    }
}