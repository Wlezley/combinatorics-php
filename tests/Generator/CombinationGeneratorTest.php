<?php

declare(strict_types=1);

namespace Lishack\Combinatorics\Tests\Generator;

use ArrayIterator;
use Lishack\Combinatorics\Combinatorics;
use Lishack\Combinatorics\Exception\InvalidCombinatoricsArgument;
use Lishack\Combinatorics\Generator\CombinationGenerator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\TestCase;

#[CoversClass(CombinationGenerator::class)]
#[CoversMethod(Combinatorics::class, 'combinations')]
final class CombinationGeneratorTest extends TestCase
{
    public function testEmptyCombination(): void
    {
        self::assertSame(
            [[]],
            self::combinations(['A', 'B', 'C'], 0),
        );
    }

    public function testSingleElementCombinations(): void
    {
        self::assertSame(
            [
                ['A'],
                ['B'],
                ['C'],
            ],
            self::combinations(['A', 'B', 'C'], 1),
        );
    }

    public function testTwoElementCombinations(): void
    {
        self::assertSame(
            [
                ['A', 'B'],
                ['A', 'C'],
                ['A', 'D'],
                ['B', 'C'],
                ['B', 'D'],
                ['C', 'D'],
            ],
            self::combinations(['A', 'B', 'C', 'D'], 2),
        );
    }

    public function testAllElementsCombination(): void
    {
        self::assertSame(
            [
                ['A', 'B', 'C'],
            ],
            self::combinations(['A', 'B', 'C'], 3),
        );
    }

    public function testEmptyInput(): void
    {
        self::assertSame(
            [[]],
            self::combinations([], 0),
        );
    }

    public function testNegativeKThrowsException(): void
    {
        $this->expectException(InvalidCombinatoricsArgument::class);

        self::combinations(['A'], -1);
    }

    public function testKGreaterThanNumberOfValuesThrowsException(): void
    {
        $this->expectException(InvalidCombinatoricsArgument::class);

        self::combinations(['A', 'B'], 3);
    }

    public function testGeneratorAcceptsTraversable(): void
    {
        self::assertSame(
            [
                ['A', 'B'],
                ['A', 'C'],
                ['B', 'C'],
            ],
            self::combinations(
                values: new ArrayIterator(['A', 'B', 'C']),
                k: 2,
            ),
        );
    }

    /**
     * @template TValue
     *
     * @param iterable<TValue> $values Source values.
     * @param int $k Number of selected elements.
     *
     * @return list<list<TValue>> All generated combinations.
     *
     * @throws InvalidCombinatoricsArgument
     */
    private static function combinations(iterable $values, int $k): array
    {
        return iterator_to_array(
            Combinatorics::combinations($values, $k),
            false,
        );
    }
}
