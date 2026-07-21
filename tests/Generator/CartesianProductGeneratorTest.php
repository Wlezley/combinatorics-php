<?php

declare(strict_types=1);

namespace Generator;

use ArrayIterator;
use Lishack\Combinatorics\Combinatorics;
use Lishack\Combinatorics\Generator\CartesianProductGenerator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\TestCase;

#[CoversClass(CartesianProductGenerator::class)]
#[CoversMethod(Combinatorics::class, 'cartesianProduct')]
final class CartesianProductGeneratorTest extends TestCase
{
    public function testEmptyProduct(): void
    {
        self::assertSame(
            [
                [],
            ],
            self::cartesianProduct([]),
        );
    }

    public function testSingleEmptySetProducesNoResults(): void
    {
        self::assertSame(
            [],
            self::cartesianProduct([
                [],
            ]),
        );
    }

    public function testProductContainingEmptySetProducesNoResults(): void
    {
        self::assertSame(
            [],
            self::cartesianProduct([
                ['A', 'B'],
                [],
                ['X', 'Y'],
            ]),
        );
    }

    public function testSingleSet(): void
    {
        self::assertSame(
            [
                ['A'],
                ['B'],
                ['C'],
            ],
            self::cartesianProduct([
                ['A', 'B', 'C'],
            ]),
        );
    }

    public function testTwoSets(): void
    {
        self::assertSame(
            [
                ['A', 1],
                ['A', 2],
                ['B', 1],
                ['B', 2],
            ],
            self::cartesianProduct([
                ['A', 'B'],
                [1, 2],
            ]),
        );
    }

    public function testThreeSets(): void
    {
        self::assertSame(
            [
                ['A', 1, 'X'],
                ['A', 1, 'Y'],
                ['A', 2, 'X'],
                ['A', 2, 'Y'],
                ['B', 1, 'X'],
                ['B', 1, 'Y'],
                ['B', 2, 'X'],
                ['B', 2, 'Y'],
            ],
            self::cartesianProduct([
                ['A', 'B'],
                [1, 2],
                ['X', 'Y'],
            ]),
        );
    }

    public function testGeneratorAcceptsTraversableSets(): void
    {
        self::assertSame(
            [
                ['A', 1],
                ['A', 2],
                ['B', 1],
                ['B', 2],
            ],
            self::cartesianProduct([
                new ArrayIterator(['A', 'B']),
                new ArrayIterator([1, 2]),
            ]),
        );
    }

    public function testGeneratedProductCountMatchesExpected(): void
    {
        $generated = self::cartesianProduct([
            range(1, 2),
            range(1, 3),
            range(1, 4),
        ]);

        self::assertCount(24, $generated);
    }

    /**
     * Returns all generated tuples as an array.
     *
     * @template TValue
     *
     * @param iterable<iterable<TValue>> $sets Source sets.
     *
     * @return list<list<TValue>>
     */
    private static function cartesianProduct(iterable $sets): array
    {
        return iterator_to_array(
            Combinatorics::cartesianProduct($sets),
            false,
        );
    }
}
