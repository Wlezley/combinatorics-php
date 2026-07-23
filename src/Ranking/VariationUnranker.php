<?php

declare(strict_types=1);

namespace Lishack\Combinatorics\Ranking;

use Brick\Math\BigInteger;
use Lishack\Combinatorics\Assert\Assert;
use Lishack\Combinatorics\Calculator\Counting\VariationCalculator;
use Lishack\Combinatorics\Utils\IterableNormalizer;

final class VariationUnranker
{
    private function __construct()
    {
        // Prevent instantiation of this class.
    }

    /**
     * Returns the variation at the specified lexicographic rank.
     *
     * @template TValue
     *
     * @param iterable<TValue> $universe Ordered set of all available values.
     * @param BigInteger|int|string $rank Zero-based lexicographic rank.
     * @param int $k Number of selected values.
     *
     * @return list<TValue>
     */
    public static function unrank(
        iterable $universe,
        BigInteger|int|string $rank,
        int $k,
    ): array {
        $universe = IterableNormalizer::toList($universe);

        $n = count($universe);

        Assert::notNegativeInteger($k);
        Assert::lessThanEq($k, $n);

        $rank = BigInteger::of($rank);

        $count = VariationCalculator::calculate(
            n: $n,
            k: $k,
        );

        Assert::rankInRange(
            rank: $rank,
            count: $count,
        );

        $available = $universe;
        $variation = [];

        for ($position = 0; $position < $k; $position++) {
            $blockSize = VariationCalculator::calculate(
                n: $n - $position - 1,
                k: $k - $position - 1,
            );

            $digit = $rank->quotient($blockSize)->toInt();

            $variation[] = $available[$digit];

            array_splice($available, $digit, 1);

            $rank = $rank->remainder($blockSize);
        }

        return $variation;
    }
}
