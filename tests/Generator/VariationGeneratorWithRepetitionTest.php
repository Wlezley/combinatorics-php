<?php

declare(strict_types=1);

namespace Generator;

use ArrayIterator;
use Lishack\Combinatorics\Combinatorics;
use Lishack\Combinatorics\Exception\InvalidCombinatoricsArgument;
use Lishack\Combinatorics\Generator\VariationGenerator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\TestCase;

#[CoversClass(VariationGenerator::class)]
#[CoversMethod(Combinatorics::class, 'variationsWithRepetition')]
final class VariationGeneratorWithRepetitionTest extends TestCase
{
    public function testEmptyVariation(): void
    {
        self::assertSame(
            [[]],
            self::variationsWithRepetition([], 0),
        );
    }

    public function testEmptyValuesWithPositiveK(): void
    {
        self::assertSame(
            [],
            self::variationsWithRepetition([], 1),
        );
    }

    public function testSingleElementVariations(): void
    {
        self::assertSame(
            [
                ['A'],
                ['B'],
                ['C'],
            ],
            self::variationsWithRepetition(['A', 'B', 'C'], 1),
        );
    }

    public function testTwoElementVariations(): void
    {
        self::assertSame(
            [
                ['A', 'A'],
                ['A', 'B'],
                ['A', 'C'],
                ['B', 'A'],
                ['B', 'B'],
                ['B', 'C'],
                ['C', 'A'],
                ['C', 'B'],
                ['C', 'C'],
            ],
            self::variationsWithRepetition(['A', 'B', 'C'], 2),
        );
    }

    public function testThreeElementVariations(): void
    {
        self::assertSame(
            [
                ['A', 'A', 'A'],
                ['A', 'A', 'B'],
                ['A', 'A', 'C'],
                ['A', 'B', 'A'],
                ['A', 'B', 'B'],
                ['A', 'B', 'C'],
                ['A', 'C', 'A'],
                ['A', 'C', 'B'],
                ['A', 'C', 'C'],

                ['B', 'A', 'A'],
                ['B', 'A', 'B'],
                ['B', 'A', 'C'],
                ['B', 'B', 'A'],
                ['B', 'B', 'B'],
                ['B', 'B', 'C'],
                ['B', 'C', 'A'],
                ['B', 'C', 'B'],
                ['B', 'C', 'C'],

                ['C', 'A', 'A'],
                ['C', 'A', 'B'],
                ['C', 'A', 'C'],
                ['C', 'B', 'A'],
                ['C', 'B', 'B'],
                ['C', 'B', 'C'],
                ['C', 'C', 'A'],
                ['C', 'C', 'B'],
                ['C', 'C', 'C'],
            ],
            self::variationsWithRepetition(['A', 'B', 'C'], 3),
        );
    }

    public function testKGreaterThanNumberOfValues(): void
    {
        self::assertSame(
            [
                ['A', 'A', 'A'],
            ],
            self::variationsWithRepetition(['A'], 3),
        );
    }

    public function testNegativeKThrowsException(): void
    {
        $this->expectException(InvalidCombinatoricsArgument::class);

        self::variationsWithRepetition(['A'], -1);
    }

    public function testGeneratorAcceptsTraversable(): void
    {
        self::assertSame(
            [
                ['A', 'A'],
                ['A', 'B'],
                ['A', 'C'],
                ['B', 'A'],
                ['B', 'B'],
                ['B', 'C'],
                ['C', 'A'],
                ['C', 'B'],
                ['C', 'C'],
            ],
            self::variationsWithRepetition(
                values: new ArrayIterator(['A', 'B', 'C']),
                k: 2,
            ),
        );
    }

    public function testGeneratedVariationCountMatchesCalculator(): void
    {
        self::assertCount(
            Combinatorics::variationsWithRepetitionCount(5, 3)->toInt(),
            self::variationsWithRepetition(range(1, 5), 3),
        );
    }

    /**
     * @template TValue
     *
     * @param iterable<TValue> $values Source values.
     * @param int $k Number of selected elements.
     *
     * @return list<list<TValue>> All generated variations.
     *
     * @throws InvalidCombinatoricsArgument
     */
    private static function variationsWithRepetition(iterable $values, int $k): array
    {
        return iterator_to_array(
            Combinatorics::variationsWithRepetition($values, $k),
            false,
        );
    }
}
