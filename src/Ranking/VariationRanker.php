<?php

declare(strict_types=1);

namespace Lishack\Combinatorics\Ranking;

use Brick\Math\BigInteger;
use Lishack\Combinatorics\Assert\Assert;
use Lishack\Combinatorics\Calculator\Counting\VariationCalculator;
use Lishack\Combinatorics\Internal\IterableNormalizer;
use Lishack\Combinatorics\Internal\ValueIndexer;

final class VariationRanker
{
    private function __construct()
    {
        // Prevent instantiation of this class.
    }

    /**
     * Calculates the lexicographic rank of a variation.
     *
     * @template TValue
     *
     * @param iterable<TValue> $universe Ordered set of all available values.
     * @param iterable<TValue> $variation Variation to rank.
     * @param (callable(TValue): (int|string))|null $keySelector Maps custom values to unique integer or string keys.
     *
     * @return BigInteger Zero-based lexicographic rank.
     */
    public static function rank(
        iterable $universe,
        iterable $variation,
        ?callable $keySelector = null,
    ): BigInteger {
        $universe = IterableNormalizer::toList($universe);
        $variation = IterableNormalizer::toList($variation);

        $n = count($universe);
        $k = count($variation);

        Assert::lessThanEq($k, $n);

        $lehmerDigits = ValueIndexer::resolveLehmerDigits(
            universe: $universe,
            values: $variation,
            keySelector: $keySelector,
        );

        $rank = BigInteger::zero();

        foreach ($lehmerDigits as $position => $digit) {
            if ($digit === 0) {
                continue;
            }

            $rank = $rank->plus(
                VariationCalculator::calculate(
                    n: $n - $position - 1,
                    k: $k - $position - 1,
                )->multipliedBy($digit),
            );
        }

        return $rank;
    }
}
