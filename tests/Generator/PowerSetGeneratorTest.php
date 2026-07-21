<?php

declare(strict_types=1);

namespace Generator;

use ArrayIterator;
use Lishack\Combinatorics\Combinatorics;
use Lishack\Combinatorics\Generator\PowerSetGenerator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\TestCase;

#[CoversClass(PowerSetGenerator::class)]
#[CoversMethod(Combinatorics::class, 'powerSet')]
final class PowerSetGeneratorTest extends TestCase
{
    public function testEmptySet(): void
    {
        self::assertSame(
            [
                [],
            ],
            self::powerSet([]),
        );
    }

    public function testSingleElementSet(): void
    {
        self::assertSame(
            [
                [],
                ['A'],
            ],
            self::powerSet(['A']),
        );
    }

    public function testTwoElementSet(): void
    {
        self::assertSame(
            [
                [],
                ['A'],
                ['B'],
                ['A', 'B'],
            ],
            self::powerSet(['A', 'B']),
        );
    }

    public function testThreeElementSet(): void
    {
        self::assertSame(
            [
                [],
                ['A'],
                ['B'],
                ['C'],
                ['A', 'B'],
                ['A', 'C'],
                ['B', 'C'],
                ['A', 'B', 'C'],
            ],
            self::powerSet(['A', 'B', 'C']),
        );
    }

    public function testGeneratorAcceptsTraversable(): void
    {
        self::assertSame(
            [
                [],
                ['A'],
                ['B'],
                ['A', 'B'],
            ],
            self::powerSet(
                new ArrayIterator(['A', 'B']),
            ),
        );
    }

    public function testGeneratedPowerSetCountMatchesExpected(): void
    {
        self::assertCount(
            1 << 5,
            self::powerSet(range(1, 5)),
        );
    }

    /**
     * @template TValue
     *
     * @param iterable<TValue> $values Source values.
     *
     * @return list<list<TValue>> All generated subsets.
     */
    private static function powerSet(iterable $values): array
    {
        return iterator_to_array(
            Combinatorics::powerSet($values),
            false,
        );
    }
}
