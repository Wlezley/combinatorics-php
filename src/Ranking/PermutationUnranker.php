<?php

declare(strict_types=1);

namespace Lishack\Combinatorics\Ranking;

use Brick\Math\BigInteger;
use Lishack\Combinatorics\Utils\IterableNormalizer;

final class PermutationUnranker
{
    private function __construct()
    {
        // Prevent instantiation of this class.
    }

    /**
     * Returns the permutation at the specified lexicographic rank.
     *
     * @template TValue
     *
     * @param iterable<TValue> $universe Ordered set of all available values.
     * @param BigInteger|int|string $rank Zero-based lexicographic rank.
     *
     * @return list<TValue>
     */
    public static function unrank(
        iterable $universe,
        BigInteger|int|string $rank,
    ): array {
        // A permutation is a special case of a variation where k = n.
        return VariationUnranker::unrank(
            universe: $universe,
            rank: $rank,
            k: count(IterableNormalizer::toList($universe)),
        );
    }
}
