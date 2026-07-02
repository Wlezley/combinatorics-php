<?php

declare(strict_types=1);

namespace Lishack\Combinatorics\Calculator\Counting;

use Brick\Math\BigInteger;
use Lishack\Combinatorics\Assert\Assert;
use Lishack\Combinatorics\Exception\InvalidCombinatoricsArgument;

final class FactorialCalculator
{
    private function __construct()
    {
        // Prevent instantiation of this class.
    }

    /**
     * @throws InvalidCombinatoricsArgument
     */
    public static function calculate(int $n): BigInteger
    {
        Assert::notNegativeInteger($n, 'Argument $n must be >= 0.');

        if ($n < 2) {
            return BigInteger::one();
        }

        $result = BigInteger::one();

        for ($i = 2; $i <= $n; ++$i) {
            $result = $result->multipliedBy($i);
        }

        return $result;
    }
}
