<?php

declare(strict_types=1);

namespace Lishack\Combinatorics;

use Brick\Math\BigInteger;
use Lishack\Combinatorics\Enum\RankingOrder;
use Lishack\Combinatorics\Exception\InvalidCombinatoricsArgument;
use Lishack\Combinatorics\Ranking\CombinationRanker;
use Lishack\Combinatorics\Ranking\CombinationUnranker;
use Lishack\Combinatorics\Ranking\PermutationRanker;
use Lishack\Combinatorics\Ranking\PermutationUnranker;
use Lishack\Combinatorics\Ranking\VariationRanker;
use Lishack\Combinatorics\Ranking\VariationUnranker;

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
     * Creates a lazy generator that yields all combinations without repetition.
     *
     * A combination is an unordered selection of k distinct elements from a set of n distinct elements.
     *
     * Each combination is generated on demand without storing all combinations in memory.
     *
     * @template TValue
     *
     * @param iterable<TValue> $values Source values.
     * @param int $k Number of selected elements.
     *
     * @return Generator\CombinationGenerator<TValue>
     *
     * @throws InvalidCombinatoricsArgument If the arguments are invalid.
     *
     * @see https://en.wikipedia.org/wiki/Combination
     */
    public static function combinations(iterable $values, int $k): Generator\CombinationGenerator
    {
        return new Generator\CombinationGenerator(
            values: $values,
            k: $k,
        );
    }

    /**
     * Creates a lazy generator that yields all combinations with repetition.
     *
     * A combination with repetition is an unordered selection of `k` elements from
     * a set where individual elements may be selected multiple times.
     *
     * Each combination is generated on demand without storing all combinations in memory.
     *
     * @template TValue
     *
     * @param iterable<TValue> $values Source values.
     * @param int $k Number of selected elements.
     *
     * @return Generator\CombinationGenerator<TValue>
     *
     * @throws InvalidCombinatoricsArgument If the arguments are invalid.
     *
     * @see https://en.wikipedia.org/wiki/Combination#Number_of_combinations_with_repetition
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
     * The power set is the set of all subsets of a given set, including the empty set and the set itself.
     *
     * Each subset is generated on demand without storing the complete power set in memory.
     *
     * @template TValue
     *
     * @param iterable<TValue> $values Source values.
     *
     * @return Generator\PowerSetGenerator<TValue>
     *
     * @see https://en.wikipedia.org/wiki/Power_set
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
     * Each permutation is generated on demand without storing all permutations in memory.
     *
     * @template TValue
     *
     * @param iterable<TValue> $values Source values.
     *
     * @return Generator\PermutationGenerator<TValue>
     *
     * @see https://en.wikipedia.org/wiki/Permutation
     */
    public static function permutations(iterable $values): Generator\PermutationGenerator
    {
        return new Generator\PermutationGenerator(
            values: $values,
        );
    }

    /**
     * Creates a lazy generator that yields all variations without repetition.
     *
     * A variation is an ordered selection of k distinct elements from a set of n distinct elements.
     *
     * Each variation is generated on demand without storing all variations in memory.
     *
     * @template TValue
     *
     * @param iterable<TValue> $values Source values.
     * @param int $k Number of selected elements.
     *
     * @return Generator\VariationGenerator<TValue>
     *
     * @throws InvalidCombinatoricsArgument If the arguments are invalid.
     *
     * @see https://en.wikipedia.org/wiki/Permutation#k-permutations_of_n
     */
    public static function variations(iterable $values, int $k): Generator\VariationGenerator
    {
        return new Generator\VariationGenerator(
            values: $values,
            k: $k,
        );
    }

    /**
     * Creates a lazy generator that yields all variations with repetition.
     *
     * A variation with repetition is an ordered selection of k elements where each element may be selected multiple times.
     *
     * Each variation is generated on demand without storing all variations in memory.
     *
     * @template TValue
     *
     * @param iterable<TValue> $values Source values.
     * @param int $k Number of selected elements.
     *
     * @return Generator\VariationGenerator<TValue>
     *
     * @throws InvalidCombinatoricsArgument If the arguments are invalid.
     *
     * @see https://en.wikipedia.org/wiki/Permutation#Permutations_with_repetition
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
     * The Cartesian product contains all ordered tuples formed by selecting exactly one element from each input set.
     *
     * Each tuple is generated on demand without storing the complete Cartesian product in memory.
     *
     * @template TValue
     *
     * @param iterable<iterable<TValue>> $sets Source sets.
     *
     * @return Generator\CartesianProductGenerator<TValue>
     *
     * @see https://en.wikipedia.org/wiki/Cartesian_product
     */
    public static function cartesianProduct(iterable $sets): Generator\CartesianProductGenerator
    {
        return new Generator\CartesianProductGenerator(
            sets: $sets,
        );
    }

    /**
     * Calculates the zero-based rank of a combination.
     *
     * The rank uniquely identifies a combination according to the selected ranking order.
     * It can later be used to reconstruct the same combination using combinationUnrank().
     *
     * @template TValue
     *
     * @param iterable<TValue> $universe The ordered set of all available values.
     * @param iterable<TValue> $combination The combination to rank.
     * @param RankingOrder $order The ranking order to use.
     * @param (callable(TValue): (int|string))|null $keySelector
     *        Optional callback that returns a unique comparison key for each element.
     *        Required when comparing objects or other non-scalar values.
     *
     * @return BigInteger The zero-based rank of the combination.
     *
     * @throws InvalidCombinatoricsArgument If the arguments are invalid.
     *
     * @see https://en.wikipedia.org/wiki/Ranking
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
     * Reconstructs a combination from its zero-based rank.
     *
     * Returns the combination corresponding to the specified rank according to the lexicographic ranking order.
     *
     * This method is the inverse operation of combinationRank().
     *
     * @template TValue
     *
     * @param iterable<TValue> $universe The ordered set of all available values.
     * @param BigInteger|int|string $rank The zero-based lexicographic rank of the combination.
     * @param int $k Number of selected elements.
     *
     * @return list<TValue> The reconstructed combination.
     *
     * @throws InvalidCombinatoricsArgument If the arguments are invalid.
     *
     * @see https://en.wikipedia.org/wiki/Ranking
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
     * The rank uniquely identifies a variation according to the lexicographic ordering.
     * It can later be used to reconstruct the same variation using variationUnrank().
     *
     * @template TValue
     *
     * @param iterable<TValue> $universe The ordered set of all available values.
     * @param iterable<TValue> $variation The variation to rank.
     * @param (callable(TValue): (int|string))|null $keySelector
     *        Optional callback that returns a unique comparison key for each element.
     *        Required when comparing objects or other non-scalar values.
     *
     * @return BigInteger The zero-based lexicographic rank.
     *
     * @throws InvalidCombinatoricsArgument If the arguments are invalid.
     *
     * @see https://en.wikipedia.org/wiki/Ranking
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

    /**
     * Reconstructs a variation from its zero-based lexicographic rank.
     *
     * Returns the variation corresponding to the specified rank in lexicographic order.
     *
     * This method is the inverse operation of variationRank().
     *
     * @template TValue
     *
     * @param iterable<TValue> $universe The ordered set of all available values.
     * @param BigInteger|int|string $rank The zero-based lexicographic rank.
     * @param int $k Number of selected elements.
     *
     * @return list<TValue> The reconstructed variation.
     *
     * @throws InvalidCombinatoricsArgument If the arguments are invalid.
     *
     * @see https://en.wikipedia.org/wiki/Ranking
     */
    public static function variationUnrank(
        iterable $universe,
        BigInteger|int|string $rank,
        int $k,
    ): array {
        return VariationUnranker::unrank(
            universe: $universe,
            rank: $rank,
            k: $k,
        );
    }

    /**
     * Calculates the zero-based lexicographic rank of a permutation.
     *
     * The rank uniquely identifies a permutation according to the lexicographic ordering.
     * It can later be used to reconstruct the same permutation using permutationUnrank().
     *
     * @template TValue
     *
     * @param iterable<TValue> $universe The ordered set of all available values.
     * @param iterable<TValue> $permutation The permutation to rank.
     * @param (callable(TValue): (int|string))|null $keySelector
     *        Optional callback that returns a unique comparison key for each element.
     *        Required when comparing objects or other non-scalar values.
     *
     * @return BigInteger The zero-based lexicographic rank.
     *
     * @throws InvalidCombinatoricsArgument If the arguments are invalid.
     *
     * @see https://en.wikipedia.org/wiki/Ranking
     */
    public static function permutationRank(
        iterable $universe,
        iterable $permutation,
        ?callable $keySelector = null,
    ): BigInteger {
        return PermutationRanker::rank(
            universe: $universe,
            permutation: $permutation,
            keySelector: $keySelector,
        );
    }

    /**
     * Reconstructs a permutation from its zero-based lexicographic rank.
     *
     * Returns the permutation corresponding to the specified rank in lexicographic order.
     *
     * This method is the inverse operation of permutationRank().
     *
     * @template TValue
     *
     * @param iterable<TValue> $universe The ordered set of all available values.
     * @param BigInteger|int|string $rank The zero-based lexicographic rank.
     *
     * @return list<TValue> The reconstructed permutation.
     *
     * @throws InvalidCombinatoricsArgument If the arguments are invalid.
     *
     * @see https://en.wikipedia.org/wiki/Ranking
     */
    public static function permutationUnrank(
        iterable $universe,
        BigInteger|int|string $rank,
    ): array {
        return PermutationUnranker::unrank(
            universe: $universe,
            rank: $rank,
        );
    }
}
