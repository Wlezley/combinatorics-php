<?php

declare(strict_types=1);

namespace Lishack\Combinatorics\Calculator\Counting;

use Brick\Math\BigInteger;
use Lishack\Combinatorics\Assert\Assert;
use Lishack\Combinatorics\Exception\InvalidCombinatoricsArgument;

final class VariationCalculator
{
    private function __construct()
    {
        // Prevent instantiation of this class.
    }

    /**
     * Calculates the number of variations without repetition.
     *
     * Mathematical definition:
     * V(n, k) = n! / (n-k)!
     *
     * @throws InvalidCombinatoricsArgument
     */
    public static function calculate(int $n, int $k): BigInteger
    {
        Assert::notNegativeInteger($n, 'Argument $n must be >= 0.');
        Assert::notNegativeInteger($k, 'Argument $k must be >= 0.');
        Assert::lessThanEq($k, $n, 'Argument $k must be <= $n.');

        if ($k === 0) {
            return BigInteger::one();
        }

        if ($k === 1) {
            return BigInteger::of($n);
        }

        $result = BigInteger::one();

        for ($i = $n - $k + 1; $i <= $n; ++$i) {
            $result = $result->multipliedBy($i);
        }

        return $result;
    }

    /**
     * Calculates the number of variations with repetition.
     *
     * Mathematical definition:
     * V'(n, k) = n^k
     *
     * @throws InvalidCombinatoricsArgument
     */
    public static function calculateWithRepetition(int $n, int $k): BigInteger
    {
        Assert::notNegativeInteger($n, 'Argument $n must be >= 0.');
        Assert::notNegativeInteger($k, 'Argument $k must be >= 0.');

        if ($k === 0) {
            return BigInteger::one();
        }

        if ($n === 0) { // Must follow the ($k === 0) check because 0^0 = 1 in this context.
            return BigInteger::zero();
        }

        if ($k === 1) {
            return BigInteger::of($n);
        }

        return BigInteger::of($n)->power($k);
    }
}
