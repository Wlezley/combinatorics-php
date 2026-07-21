<?php

declare(strict_types=1);

namespace Lishack\Combinatorics\Ranking;

use Brick\Math\BigInteger;
use Lishack\Combinatorics\Assert\Assert;
use Lishack\Combinatorics\Calculator\Counting\BinomialCalculator;
use Lishack\Combinatorics\Internal\IterableNormalizer;

/**
 * Unranks combinations in lexicographic order.
 */
final class CombinationUnranker
{
    private function __construct()
    {
        // Prevent instantiation of this class.
    }

    /**
     * Returns the combination with the given rank.
     *
     * @template TValue
     *
     * @param iterable<TValue> $universe
     *
     * @return list<TValue>
     */
    public static function unrank(
        iterable $universe,
        BigInteger|int|string $rank,
        int $k,
    ): array {
        $values = IterableNormalizer::toList($universe);
        $rank = BigInteger::of($rank);
        $n = count($values);

        Assert::notNegativeInteger($k);
        Assert::lessThanEq($k, $n);

        $count = BinomialCalculator::calculate($n, $k);

        Assert::rankInRange($rank, $count);

        $indices = self::unrankLexicographic(
            rank: $rank,
            n: $n,
            k: $k,
        );

        return self::mapIndicesToValues(
            indices: $indices,
            values: $values,
        );
    }

    /**
     * Calculates the lexicographic unranking.
     *
     * @return list<int>
     */
    private static function unrankLexicographic(
        BigInteger $rank,
        int $n,
        int $k,
    ): array {
        $indices = [];
        $candidate = 0;

        for ($position = 0; $position < $k; ++$position) {
            for (; $candidate < $n; ++$candidate) {
                $blockSize = BinomialCalculator::calculate(
                    $n - $candidate - 1,
                    $k - $position - 1,
                );

                if ($rank->isLessThan($blockSize)) {
                    $indices[] = $candidate;
                    ++$candidate;

                    break;
                }

                $rank = $rank->minus($blockSize);
            }
        }

        if (count($indices) !== $k) {
            throw new \LogicException(
                'Internal error: failed to reconstruct the combination from the specified rank.',
            );
        }

        return $indices;
    }

    /**
     * Converts indices back to values.
     *
     * @template TValue
     *
     * @param list<int> $indices
     * @param list<TValue> $values
     *
     * @return list<TValue>
     */
    private static function mapIndicesToValues(array $indices, array $values): array
    {
        $result = [];

        foreach ($indices as $index) {
            $result[] = $values[$index];
        }

        return $result;
    }
}
