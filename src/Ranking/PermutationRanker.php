<?php

declare(strict_types=1);

namespace Lishack\Combinatorics\Ranking;

use Brick\Math\BigInteger;

final class PermutationRanker
{
    private function __construct()
    {
        // Prevent instantiation of this class.
    }

    /**
     * Calculates the lexicographic rank of a permutation.
     *
     * @template TValue
     *
     * @param iterable<TValue> $universe Ordered set of all available values.
     * @param iterable<TValue> $permutation Permutation to rank.
     * @param (callable(TValue): (int|string))|null $keySelector Maps custom values to unique integer or string keys.
     *
     * @return BigInteger Zero-based lexicographic rank.
     */
    public static function rank(
        iterable $universe,
        iterable $permutation,
        ?callable $keySelector = null,
    ): BigInteger {
        // A permutation is a special case of a variation where k = n.
        return VariationRanker::rank(
            universe: $universe,
            variation: $permutation,
            keySelector: $keySelector,
        );
    }
}
