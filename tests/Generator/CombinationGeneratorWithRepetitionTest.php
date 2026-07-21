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
            self::combinations([], 0),
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
                ['A', 'A'],
                ['A', 'B'],
                ['A', 'C'],
                ['B', 'B'],
                ['B', 'C'],
                ['C', 'C'],
            ],
            self::combinations(['A', 'B', 'C'], 2),
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
            self::combinations(['A', 'B', 'C'], 3),
        );
    }

    public function testKGreaterThanNumberOfValues(): void
    {
        self::assertSame(
            [
                ['A', 'A', 'A'],
            ],
            self::combinations(['A'], 3),
        );
    }

    public function testNegativeKThrowsException(): void
    {
        $this->expectException(InvalidCombinatoricsArgument::class);

        iterator_to_array(
            Combinatorics::combinationsWithRepetition(['A'], -1),
        );
    }

    public function testGeneratorAcceptsTraversable(): void
    {
        $values = new ArrayIterator(['A', 'B', 'C']);

        self::assertSame(
            [
                ['A', 'A'],
                ['A', 'B'],
                ['A', 'C'],
                ['B', 'B'],
                ['B', 'C'],
                ['C', 'C'],
            ],
            self::combinations($values, 2),
        );
    }

    public function testGeneratedCombinationCountMatchesCalculator(): void
    {
        $generated = iterator_to_array(
            Combinatorics::combinationsWithRepetition(range(1, 5), 3),
            false,
        );

        self::assertCount(
            Combinatorics::combinationsWithRepetitionCount(5, 3)->toInt(),
            $generated,
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
            Combinatorics::combinationsWithRepetition($values, $k),
            false,
        );
    }
}
