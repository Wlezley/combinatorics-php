<?php

declare(strict_types=1);

namespace Calculator\Counting;

use Brick\Math\BigInteger;
use Lishack\Combinatorics\Calculator\Counting\VariationCalculator;
use Lishack\Combinatorics\Exception\InvalidCombinatoricsArgument;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(VariationCalculator::class)]
class VariationCalculatorTest extends TestCase
{
    #[DataProvider('provideCalculate')]
    public function testCalculate(
        int $n,
        int $k,
        string $expected,
    ): void {
        self::assertTrue(
            VariationCalculator::calculate($n, $k)
                ->isEqualTo(BigInteger::of($expected))
        );
    }

    /**
     * @return iterable<string, array{int, int, string}>
     */
    public static function provideCalculate(): iterable
    {
        yield '0 of 0' => [0, 0, '1'];
        yield '1 of 0' => [1, 0, '1'];
        yield '5 of 1' => [5, 1, '5'];

        yield '5 of 2' => [5, 2, '20'];
        yield '5 of 3' => [5, 3, '60'];
        yield '5 of 5' => [5, 5, '120'];

        yield '10 of 3' => [10, 3, '720'];
        yield '10 of 5' => [10, 5, '30240'];
        yield '20 of 10' => [20, 10, '670442572800'];
    }

    #[DataProvider('provideInvalidArguments')]
    public function testCalculateThrowsException(
        int $n,
        int $k,
    ): void {
        $this->expectException(InvalidCombinatoricsArgument::class);

        VariationCalculator::calculate($n, $k);
    }

    /**
     * @return iterable<string, array{int, int}>
     */
    public static function provideInvalidArguments(): iterable
    {
        yield 'negative n' => [-1, 0];
        yield 'negative k' => [5, -1];
        yield 'k greater than n' => [5, 6];
        yield 'both negative' => [-1, -1];
    }

    #[DataProvider('provideCalculateWithRepetition')]
    public function testCalculateWithRepetition(
        int $n,
        int $k,
        string $expected,
    ): void {
        self::assertTrue(
            VariationCalculator::calculateWithRepetition($n, $k)
                ->isEqualTo(BigInteger::of($expected))
        );
    }

    /**
     * @return iterable<string, array{int, int, string}>
     */
    public static function provideCalculateWithRepetition(): iterable
    {
        yield '0^0' => [0, 0, '1'];
        yield '0^5' => [0, 5, '0'];

        yield '1^10' => [1, 10, '1'];

        yield '5^0' => [5, 0, '1'];
        yield '5^1' => [5, 1, '5'];
        yield '5^2' => [5, 2, '25'];
        yield '5^3' => [5, 3, '125'];

        yield '10^5' => [10, 5, '100000'];
        yield '20^10' => [20, 10, '10240000000000'];
    }

    #[DataProvider('provideInvalidArgumentsWithRepetition')]
    public function testCalculateWithRepetitionThrowsException(
        int $n,
        int $k,
    ): void {
        $this->expectException(InvalidCombinatoricsArgument::class);

        VariationCalculator::calculateWithRepetition($n, $k);
    }

    /**
     * @return iterable<string, array{int, int}>
     */
    public static function provideInvalidArgumentsWithRepetition(): iterable
    {
        yield 'negative n' => [-1, 0];
        yield 'negative k' => [5, -1];
        yield 'both negative' => [-1, -1];
    }
}
