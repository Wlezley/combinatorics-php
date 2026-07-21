<?php

declare(strict_types=1);

namespace Generator;

use ArrayIterator;
use Lishack\Combinatorics\Combinatorics;
use Lishack\Combinatorics\Exception\InvalidCombinatoricsArgument;
use Lishack\Combinatorics\Generator\CombinationGenerator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\TestCase;

#[CoversClass(CombinationGenerator::class)]
#[CoversMethod(Combinatorics::class, 'combinationsWithRepetition')]
final class CombinationGeneratorWithRepetitionTest extends TestCase
{
    public function testEmptyCombination(): void
    {
        self::assertSame(
            [[]],
            self::combinationsWithRepetition([], 0),
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
            self::combinationsWithRepetition(['A', 'B', 'C'], 1),
        );
    }

    public function testTwoElementCombinations(): void
    {
        self::assertSame(
            [
                ['A', 'A'],
                ['A', 'B'],
                ['A', 'C'],
                ['B', 'B'],
                ['B', 'C'],
                ['C', 'C'],
            ],
            self::combinationsWithRepetition(['A', 'B', 'C'], 2),
        );
    }

    public function testThreeElementCombinations(): void
    {
        self::assertSame(
            [
                ['A', 'A', 'A'],
                ['A', 'A', 'B'],
                ['A', 'A', 'C'],
                ['A', 'B', 'B'],
                ['A', 'B', 'C'],
                ['A', 'C', 'C'],
                ['B', 'B', 'B'],
                ['B', 'B', 'C'],
                ['B', 'C', 'C'],
                ['C', 'C', 'C'],
            ],
            self::combinationsWithRepetition(['A', 'B', 'C'], 3),
        );
    }

    public function testKGreaterThanNumberOfValues(): void
    {
        self::assertSame(
            [
                ['A', 'A', 'A'],
            ],
            self::combinationsWithRepetition(['A'], 3),
        );
    }

    public function testNegativeKThrowsException(): void
    {
        $this->expectException(InvalidCombinatoricsArgument::class);

        self::combinationsWithRepetition(['A'], -1);
    }

    public function testGeneratorAcceptsTraversable(): void
    {
        self::assertSame(
            [
                ['A', 'A'],
                ['A', 'B'],
                ['A', 'C'],
                ['B', 'B'],
                ['B', 'C'],
                ['C', 'C'],
            ],
            self::combinationsWithRepetition(
                values: new ArrayIterator(['A', 'B', 'C']),
                k: 2,
            ),
        );
    }

    public function testGeneratedCombinationCountMatchesCalculator(): void
    {
        self::assertCount(
            Combinatorics::combinationsWithRepetitionCount(5, 3)->toInt(),
            self::combinationsWithRepetition(range(1, 5), 3),
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
    private static function combinationsWithRepetition(iterable $values, int $k): array
    {
        return iterator_to_array(
            Combinatorics::combinationsWithRepetition($values, $k),
            false,
        );
    }
}
