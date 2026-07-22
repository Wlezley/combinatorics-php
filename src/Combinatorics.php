<?php

declare(strict_types=1);

namespace Lishack\Combinatorics;

use Brick\Math\BigInteger;
use Lishack\Combinatorics\Enum\RankingOrder;
use Lishack\Combinatorics\Exception\InvalidCombinatoricsArgument;
use Lishack\Combinatorics\Ranking\CombinationRanker;
use Lishack\Combinatorics\Ranking\CombinationUnranker;
use Lishack\Combinatorics\Ranking\VariationRanker;

final class Combinatorics
{
    private function __construct()
    {
        // Prevent instantiation of this class.
    }

    /**
     * Calculates the binomial coefficient C(n, k).
     *
     * The binomial coefficient represents the number of ways to choose
     * exactly k elements from a set of n distinct elements without
     * regard to the order of selection.
     *
     * Mathematical definition:
     * C(n, k) = n! / (k!(n-k)!)
     *
     * Returns the exact result using arbitrary-precision arithmetic.
     *
     * @param int $n Total number of distinct elements.
     * @param int $k Number of selected elements.
     *
     * @return BigInteger The binomial coefficient C(n, k).
     *
     * @throws InvalidCombinatoricsArgument
     *
     * @see https://en.wikipedia.org/wiki/Binomial_coefficient
     */
    public static function binomial(int $n, int $k): BigInteger
    {
        return Calculator\Counting\BinomialCalculator::calculate(
            n: $n,
            k: $k,
        );
    }

    /**
     * Calculates the factorial of a non-negative integer.
     *
     * Mathematical definition:
     * n! = 1 × 2 × ... × n
     *
     * By definition:
     * 0! = 1
     *
     * Returns the exact result using arbitrary-precision arithmetic.
     *
     * @param int $n Total number of distinct elements. Non-negative integer.
     *
     * @return BigInteger The factorial of n.
     *
     * @throws InvalidCombinatoricsArgument
     *
     * @see https://en.wikipedia.org/wiki/Factorial
     */
    public static function factorial(int $n): BigInteger
    {
        return Calculator\Counting\FactorialCalculator::calculate(
            n: $n
        );
    }

    /**
     * Calculates the number of variations without repetition.
     *
     * A variation without repetition is an ordered selection of k
     * distinct elements chosen from a set of n distinct elements.
     *
     * Mathematical definition:
     * V(n, k) = n! / (n-k)!
     *
     * Returns the exact result using arbitrary-precision arithmetic.
     *
     * @param int $n Total number of distinct elements.
     * @param int $k Number of selected elements.
     *
     * @return BigInteger The number of variations without repetition.
     *
     * @throws InvalidCombinatoricsArgument
     *
     * @see https://en.wikipedia.org/wiki/Variation_of_parameters
     */
    public static function variationsCount(int $n, int $k): BigInteger
    {
        return Calculator\Counting\VariationCalculator::calculate(
            n: $n,
            k: $k,
        );
    }

    /**
     * Calculates the number of variations with repetition.
     *
     * A variation with repetition is an ordered selection of k
     * elements from a set of n distinct elements where individual
     * elements may be selected multiple times.
     *
     * Mathematical definition:
     * n^k
     *
     * Returns the exact result using arbitrary-precision arithmetic.
     *
     * @param int $n Total number of distinct elements.
     * @param int $k Number of selected elements.
     *
     * @return BigInteger The number of variations with repetition.
     *
     * @throws InvalidCombinatoricsArgument
     *
     * @see https://en.wikipedia.org/wiki/Variation_of_parameters
     */
    public static function variationsWithRepetitionCount(int $n, int $k): BigInteger
    {
        return Calculator\Counting\VariationCalculator::calculateWithRepetition(
            n: $n,
            k: $k,
        );
    }

    /**
     * Calculates the number of permutations.
     *
     * A permutation is an ordered arrangement of all distinct elements
     * of a set.
     *
     * Mathematical definition:
     * n!
     *
     * This method is an alias for factorial().
     *
     * Returns the exact result using arbitrary-precision arithmetic.
     *
     * @param int $n Total number of distinct elements. Non-negative integer.
     *
     * @return BigInteger The number of permutations.
     *
     * @throws InvalidCombinatoricsArgument
     *
     * @see https://en.wikipedia.org/wiki/Permutation
     */
    public static function permutationsCount(int $n): BigInteger
    {
        return self::factorial(
            n: $n
        );
    }

    /**
     * Calculates the number of combinations without repetition.
     *
     * A combination is an unordered selection of k elements from
     * a set of n distinct elements.
     *
     * Mathematical definition:
     * C(n, k) = n! / (k!(n-k)!)
     *
     * This method is an alias for binomial().
     *
     * Returns the exact result using arbitrary-precision arithmetic.
     *
     * @param int $n Total number of distinct elements.
     * @param int $k Number of selected elements.
     *
     * @return BigInteger The number of combinations without repetition.
     *
     * @throws InvalidCombinatoricsArgument
     *
     * @see https://en.wikipedia.org/wiki/Combination
     */
    public static function combinationsCount(int $n, int $k): BigInteger
    {
        return self::binomial(
            n: $n,
            k: $k,
        );
    }

    /**
     * Calculates the number of combinations with repetition.
     *
     * A combination with repetition is an unordered selection of k
     * elements from a set of n distinct elements where individual
     * elements may be selected multiple times.
     *
     * Mathematical definition:
     * C(n + k - 1, k)
     *
     * Returns the exact result using arbitrary-precision arithmetic.
     *
     * @param int $n Total number of distinct elements.
     * @param int $k Number of selected elements.
     *
     * @return BigInteger The number of combinations with repetition.
     *
     * @throws InvalidCombinatoricsArgument
     *
     * @see https://en.wikipedia.org/wiki/Combination#Number_of_combinations_with_repetition
     */
    public static function combinationsWithRepetitionCount(int $n, int $k): BigInteger
    {
        return self::binomial(
            n: $n + $k - 1,
            k: $k
        );
    }

    /**
     * Creates a lazy generator that yields all combinations WITHOUT repetition.
     *
     * @template TValue
     *
     * @param iterable<TValue> $values Source values.
     * @param int $k Number of selected elements.
     *
     * @return Generator\CombinationGenerator<TValue>
     *
     * @throws InvalidCombinatoricsArgument
     */
    public static function combinations(iterable $values, int $k): Generator\CombinationGenerator
    {
        return new Generator\CombinationGenerator(
            values: $values,
            k: $k,
        );
    }

    /**
     * Creates a lazy generator that yields all combinations WITH repetition.
     *
     * @template TValue
     *
     * @param iterable<TValue> $values Source values.
     * @param int $k Number of selected elements.
     *
     * @return Generator\CombinationGenerator<TValue>
     *
     * @throws InvalidCombinatoricsArgument
     */
    public static function combinationsWithRepetition(iterable $values, int $k): Generator\CombinationGenerator
    {
        return new Generator\CombinationGenerator(
            values: $values,
            k: $k,
            allowRepetition: true,
        );
    }

    /**
     * Creates a lazy generator that yields the power set.
     *
     * The power set is the set of all subsets of the given set, including the empty set and the set itself.
     *
     * @template TValue
     *
     * @param iterable<TValue> $values Source values.
     *
     * @return Generator\PowerSetGenerator<TValue>
     */
    public static function powerSet(iterable $values): Generator\PowerSetGenerator
    {
        return new Generator\PowerSetGenerator(
            values: $values,
        );
    }

    /**
     * Creates a lazy generator that yields all permutations.
     *
     * A permutation is an ordered arrangement of all distinct elements of a set.
     *
     * @template TValue
     *
     * @param iterable<TValue> $values Source values.
     *
     * @return Generator\PermutationGenerator<TValue>
     */
    public static function permutations(iterable $values): Generator\PermutationGenerator
    {
        return new Generator\PermutationGenerator(
            values: $values,
        );
    }

    /**
     * Creates a lazy generator that yields all variations WITHOUT repetition.
     *
     * A variation is an ordered selection of distinct elements from a set.
     *
     * @template TValue
     *
     * @param iterable<TValue> $values Source values.
     * @param int $k Number of selected elements.
     *
     * @return Generator\VariationGenerator<TValue>
     *
     * @throws InvalidCombinatoricsArgument If the arguments are invalid.
     */
    public static function variations(iterable $values, int $k): Generator\VariationGenerator
    {
        return new Generator\VariationGenerator(
            values: $values,
            k: $k,
        );
    }

    /**
     * Creates a lazy generator that yields all variations WITH repetition.
     *
     * A variation with repetition is an ordered selection where each element may be chosen multiple times.
     *
     * @template TValue
     *
     * @param iterable<TValue> $values Source values.
     * @param int $k Number of selected elements.
     *
     * @return Generator\VariationGenerator<TValue>
     *
     * @throws InvalidCombinatoricsArgument If the arguments are invalid.
     */
    public static function variationsWithRepetition(iterable $values, int $k): Generator\VariationGenerator
    {
        return new Generator\VariationGenerator(
            values: $values,
            k: $k,
            allowRepetition: true,
        );
    }

    /**
     * Creates a lazy generator that yields the Cartesian product of the given sets.
     *
     * Each generated value contains one element from every input set.
     *
     * @template TValue
     *
     * @param iterable<iterable<TValue>> $sets Source sets.
     *
     * @return Generator\CartesianProductGenerator<TValue>
     */
    public static function cartesianProduct(iterable $sets): Generator\CartesianProductGenerator
    {
        return new Generator\CartesianProductGenerator(
            sets: $sets,
        );
    }

    /**
     * Calculates the rank of a combination.
     *
     * @template TValue
     *
     * @param iterable<TValue> $universe The ordered set of all available values.
     * @param iterable<TValue> $combination The combination to rank.
     * @param RankingOrder $order The ranking order to use.
     * @param (callable(TValue): (int|string))|null $keySelector Maps custom values to unique integer or string keys.
     */
    public static function combinationRank(
        iterable $universe,
        iterable $combination,
        RankingOrder $order = RankingOrder::Lexicographic,
        ?callable $keySelector = null,
    ): BigInteger {
        return CombinationRanker::rank(
            universe: $universe,
            combination: $combination,
            order: $order,
            keySelector: $keySelector,
        );
    }

    /**
     * Calculates the combination for the specified lexicographic rank.
     *
     * @template TValue
     *
     * @param iterable<TValue> $universe The ordered set of all available values.
     * @param BigInteger|int|string $rank The lexicographic rank of the combination.
     * @param int $k The number of elements in the combination.
     *
     * @return list<TValue>
     */
    public static function combinationUnrank(
        iterable $universe,
        BigInteger|int|string $rank,
        int $k,
    ): array {
        return CombinationUnranker::unrank(
            universe: $universe,
            rank: $rank,
            k: $k,
        );
    }

    /**
     * Calculates the zero-based lexicographic rank of a variation.
     *
     * @template TValue
     *
     * @param iterable<TValue> $universe The ordered set of all available values.
     * @param iterable<TValue> $variation The variation to rank.
     * @param (callable(TValue): (int|string))|null $keySelector Maps custom values to unique integer or string keys.
     */
    public static function variationRank(
        iterable $universe,
        iterable $variation,
        ?callable $keySelector = null,
    ): BigInteger {
        return VariationRanker::rank(
            universe: $universe,
            variation: $variation,
            keySelector: $keySelector,
        );
    }
}
