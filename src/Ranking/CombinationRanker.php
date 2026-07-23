<?php

declare(strict_types=1);

namespace Lishack\Combinatorics\Ranking;

use Brick\Math\BigInteger;
use Lishack\Combinatorics\Calculator\Counting\BinomialCalculator;
use Lishack\Combinatorics\Enum\RankingOrder;
use Lishack\Combinatorics\Exception\InvalidCombinatoricsArgument;
use Lishack\Combinatorics\Utils\ValueIndexer;

/**
 * Ranks combinations in lexicographic or colexicographic order.
 */
final class CombinationRanker
{
    private function __construct()
    {
        // Prevent instantiation of this class.
    }

    /**
     * Calculates the rank of a combination.
     *
     * @template TValue
     *
     * @param iterable<TValue> $universe Ordered set of all available values.
     * @param iterable<TValue> $combination Combination to rank.
     * @param (callable(TValue): (int|string))|null $keySelector Maps custom values to unique integer or string keys.
     *
     * @return BigInteger Zero-based rank.
     */
    public static function rank(
        iterable $universe,
        iterable $combination,
        RankingOrder $order = RankingOrder::Lexicographic,
        ?callable $keySelector = null,
    ): BigInteger {
        $lookup = ValueIndexer::createLookup(
            values: $universe,
            keySelector: $keySelector,
        );

        $indices = ValueIndexer::resolveIndices(
            values: $combination,
            lookup: $lookup,
            keySelector: $keySelector,
        );

        self::validateIndices($indices);

        $n = count($lookup);
        $k = count($indices);

        return match ($order) {
            RankingOrder::Lexicographic => self::rankLexicographic($indices, $n, $k),
            RankingOrder::Colexicographic => self::rankColexicographic($indices),
        };
    }

    /**
     * Validates that the indices are strictly increasing.
     *
     * @param list<int> $indices
     */
    private static function validateIndices(array $indices): void
    {
        for ($i = 1, $count = count($indices); $i < $count; ++$i) {
            if ($indices[$i] <= $indices[$i - 1]) {
                throw new InvalidCombinatoricsArgument(
                    'Combination values must be unique and preserve the order of the source collection.',
                );
            }
        }
    }

    /**
     * Calculates the lexicographic rank.
     *
     * @param list<int> $indices
     */
    private static function rankLexicographic(array $indices, int $n, int $k): BigInteger
    {
        $rank = BigInteger::zero();
        $previous = -1;

        foreach ($indices as $i => $index) {
            for ($j = $previous + 1; $j < $index; ++$j) {
                $rank = $rank->plus(
                    BinomialCalculator::calculate(
                        $n - $j - 1,
                        $k - $i - 1,
                    ),
                );
            }

            $previous = $index;
        }

        return $rank;
    }

    /**
     * Calculates the colexicographic rank.
     *
     * @param list<int> $indices
     */
    private static function rankColexicographic(array $indices): BigInteger
    {
        $rank = BigInteger::zero();

        foreach ($indices as $i => $index) {
            // C(n, k) = 0 for k > n.
            // BinomialCalculator treats such inputs as invalid, so we skip these zero-valued terms.
            if ($index < $i + 1) {
                continue;
            }

            $rank = $rank->plus(
                BinomialCalculator::calculate(
                    $index,
                    $i + 1,
                ),
            );
        }

        return $rank;
    }
}
