<?php

declare(strict_types=1);

namespace Calculator\Counting;

use Brick\Math\BigInteger;
use Lishack\Combinatorics\Calculator\Counting\FactorialCalculator;
use Lishack\Combinatorics\Exception\InvalidCombinatoricsArgument;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(FactorialCalculator::class)]
class FactorialCalculatorTest extends TestCase
{
    #[DataProvider('provideCalculate')]
    public function testCalculate(
        int $n,
        string $expected,
    ): void {
        self::assertTrue(
            FactorialCalculator::calculate($n)
                ->isEqualTo(BigInteger::of($expected))
        );
    }

    /**
     * @return iterable<string, array{int, string}>
     */
    public static function provideCalculate(): iterable
    {
        yield '0!' => [0, '1'];
        yield '1!' => [1, '1'];
        yield '2!' => [2, '2'];
        yield '3!' => [3, '6'];
        yield '5!' => [5, '120'];
        yield '10!' => [10, '3628800'];
        yield '20!' => [20, '2432902008176640000'];

        yield '50!' => [
            50,
            '30414093201713378043612608166064768844377641568960512000000000000',
        ];

        yield '100!' => [
            100,
            // phpcs:ignore SlevomatCodingStandard.Files.LineLength.LineTooLong
            '93326215443944152681699238856266700490715968264381621468592963895217599993229915608941463976156518286253697920827223758251185210916864000000000000000000000000',
        ];
    }

    #[DataProvider('provideInvalidArguments')]
    public function testCalculateThrowsException(int $n): void
    {
        $this->expectException(InvalidCombinatoricsArgument::class);

        FactorialCalculator::calculate($n);
    }

    /**
     * @return iterable<string, array{int}>
     */
    public static function provideInvalidArguments(): iterable
    {
        yield 'negative one' => [-1];
        yield 'negative ten' => [-10];
        yield 'minimum integer' => [PHP_INT_MIN];
    }
}
