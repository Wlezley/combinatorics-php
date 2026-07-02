<?php

declare(strict_types=1);

namespace Lishack\Combinatorics\Calculator\Counting;

use Brick\Math\BigInteger;
use Lishack\Combinatorics\Assert\Assert;
use Lishack\Combinatorics\Exception\InvalidCombinatoricsArgument;

final class BinomialCalculator
{
    private function __construct()
    {
        // Prevent instantiation of this class.
    }

    /**
     * @throws InvalidCombinatoricsArgument
     */
    public static function calculate(int $n, int $k): BigInteger
    {
        Assert::notNegativeInteger($n, 'Argument $n must be >= 0.');
        Assert::notNegativeInteger($k, 'Argument $k must be >= 0.');
        Assert::lessThanEq($k, $n, 'Argument $k must be <= $n.');

        $k = min($k, $n - $k);

        if ($k === 0) {
            return BigInteger::one();
        }

        if ($k === 1) {
            return BigInteger::of($n);
        }

        $start = $n - $k;
        $result = BigInteger::one();

        for ($i = 1; $i <= $k; ++$i) {
            $result = $result
                ->multipliedBy($start + $i)
                ->dividedBy($i);
        }

        return $result;
    }
}
