<?php

declare(strict_types=1);

namespace Lishack\Combinatorics\Tests\Calculator\Counting;

use Brick\Math\BigInteger;
use Lishack\Combinatorics\Calculator\Counting\BinomialCalculator;
use Lishack\Combinatorics\Exception\InvalidCombinatoricsArgument;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(BinomialCalculator::class)]
class BinomialCalculatorTest extends TestCase
{
    #[DataProvider('provideCalculate')]
    public function testCalculate(
        int $n,
        int $k,
        string $expected,
    ): void {
        self::assertTrue(
            BinomialCalculator::calculate($n, $k)
                ->isEqualTo(BigInteger::of($expected))
        );
    }

    /**
     * @return iterable<string, array{int, int, string}>
     */
    public static function provideCalculate(): iterable
    {
        yield '0 choose 0' => [0, 0, '1'];
        yield '1 choose 0' => [1, 0, '1'];
        yield '1 choose 1' => [1, 1, '1'];

        yield '2 choose 1' => [2, 1, '2'];
        yield '5 choose 2' => [5, 2, '10'];
        yield '6 choose 3' => [6, 3, '20'];
        yield '10 choose 3' => [10, 3, '120'];
        yield '10 choose 5' => [10, 5, '252'];
        yield '10 choose 8 (symmetry)' => [10, 8, '45'];

        yield '20 choose 10' => [20, 10, '184756'];
        yield '30 choose 15' => [30, 15, '155117520'];
        yield '49 choose 6 (lottery)' => [49, 6, '13983816'];

        yield '100 choose 50' => [
            100,
            50,
            '100891344545564193334812497256',
        ];
    }

    #[DataProvider('provideSymmetry')]
    public function testSymmetry(int $n, int $k): void
    {
        self::assertTrue(
            BinomialCalculator::calculate($n, $k)
                ->isEqualTo(
                    BinomialCalculator::calculate($n, $n - $k)
                )
        );
    }

    /**
     * @return iterable<string, array{int, int}>
     */
    public static function provideSymmetry(): iterable
    {
        yield '10' => [10, 3];
        yield '25' => [25, 7];
        yield '50' => [50, 11];
        yield '100' => [100, 37];
    }

    #[DataProvider('provideInvalidArguments')]
    public function testCalculateThrowsException(
        int $n,
        int $k,
    ): void {
        $this->expectException(InvalidCombinatoricsArgument::class);

        BinomialCalculator::calculate($n, $k);
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
}
