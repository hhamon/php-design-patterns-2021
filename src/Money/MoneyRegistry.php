<?php

declare(strict_types=1);

namespace App\Money;

use Money\Currencies\ISOCurrencies;
use Money\Currency;
use Money\Money;
use Money\Parser\DecimalMoneyParser;

/**
 * This class acts as a "Flyweight Factory" for creating Money "Flyweight" instances.
 */
final class MoneyRegistry
{
    /**
     * @var Money[]
     */
    private static array $moneys = [];

    private DecimalMoneyParser $parser;

    public function __construct()
    {
        $this->parser = new DecimalMoneyParser(new ISOCurrencies());
    }

    public function createMoney(string $value): Money
    {
        if ($money = (static::$moneys[$value] ?? null)) {
            return $money;
        }

        [$currency, $amount] = \explode(' ', $value);

        static::$moneys[$value] = $money = $this->parser->parse($amount, new Currency($currency));

        return $money;
    }
}