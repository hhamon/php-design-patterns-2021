<?php

declare(strict_types=1);

namespace App\Coupon\Factory;

use App\Entity\Coupon\ValueCoupon;
use Assert\Assertion;
use Money\Currencies\ISOCurrencies;
use Money\Currency;
use Money\Money;
use Money\Parser\DecimalMoneyParser;

final class ValueCouponFactory extends AbstractCouponFactory
{
    private DecimalMoneyParser $parser;

    public function __construct()
    {
        $this->parser = new DecimalMoneyParser(new ISOCurrencies());
    }

    protected function issueCoupon(string $code, array $options): ValueCoupon
    {
        Assertion::notEmpty($options['discount'] ?? '', 'Value coupon discount is required.');

        return new ValueCoupon($code, $this->parse($options['discount']));
    }

    private function parse(string $discount): Money
    {
        [$currency, $amount] = \explode(' ', $discount);

        return $this->parser->parse($amount, new Currency($currency));
    }
}