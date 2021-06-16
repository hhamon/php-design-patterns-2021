<?php

declare(strict_types=1);

namespace App\Coupon\CodeGenerator;

interface CouponCodeGeneratorInterface
{
    public function generate(array $options = []): string;
}