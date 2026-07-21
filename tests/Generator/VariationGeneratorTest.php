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
#[CoversMethod(Combinatorics::class, 'variations')]
final class VariationGeneratorTest extends TestCase
{
    public function testEmptyVariation(): void
    {
        self::assertSame(
            [[]],
            self::variations([], 0),
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
            self::variations(['A', 'B', 'C'], 1),
        );
    }

    public function testTwoElementVariations(): void
    {
        self::assertSame(
            [
                ['A', 'B'],
                ['A', 'C'],
                ['B', 'A'],
                ['B', 'C'],
                ['C', 'A'],
                ['C', 'B'],
            ],
            self::variations(['A', 'B', 'C'], 2),
        );
    }

    public function testThreeElementVariations(): void
    {
        self::assertSame(
            [
                ['A', 'B', 'C'],
                ['A', 'C', 'B'],
                ['B', 'A', 'C'],
                ['B', 'C', 'A'],
                ['C', 'A', 'B'],
                ['C', 'B', 'A'],
            ],
            self::variations(['A', 'B', 'C'], 3),
        );
    }

    public function testKGreaterThanNumberOfValuesThrowsException(): void
    {
        $this->expectException(InvalidCombinatoricsArgument::class);

        self::variations(['A', 'B'], 3);
    }

    public function testNegativeKThrowsException(): void
    {
        $this->expectException(InvalidCombinatoricsArgument::class);

        self::variations(['A'], -1);
    }

    public function testGeneratorAcceptsTraversable(): void
    {
        self::assertSame(
            [
                ['A', 'B'],
                ['A', 'C'],
                ['B', 'A'],
                ['B', 'C'],
                ['C', 'A'],
                ['C', 'B'],
            ],
            self::variations(
                values: new ArrayIterator(['A', 'B', 'C']),
                k: 2,
            ),
        );
    }

    public function testGeneratedVariationCountMatchesCalculator(): void
    {
        self::assertCount(
            Combinatorics::variationsCount(5, 3)->toInt(),
            self::variations(range(1, 5), 3),
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
    private static function variations(iterable $values, int $k): array
    {
        return iterator_to_array(
            Combinatorics::variations($values, $k),
            false,
        );
    }
}
