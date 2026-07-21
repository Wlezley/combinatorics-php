<?php

declare(strict_types=1);

namespace Lishack\Combinatorics;

use Brick\Math\BigInteger;
use Lishack\Combinatorics\Exception\InvalidCombinatoricsArgument;

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
}
