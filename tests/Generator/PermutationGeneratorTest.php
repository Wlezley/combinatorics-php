<?php

declare(strict_types=1);

namespace Generator;

use ArrayIterator;
use Lishack\Combinatorics\Combinatorics;
use Lishack\Combinatorics\Generator\PermutationGenerator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\TestCase;

#[CoversClass(PermutationGenerator::class)]
#[CoversMethod(Combinatorics::class, 'permutations')]
final class PermutationGeneratorTest extends TestCase
{
    public function testEmptyValues(): void
    {
        self::assertSame(
            [[]],
            self::permutations([]),
        );
    }

    public function testSingleElement(): void
    {
        self::assertSame(
            [
                ['A'],
            ],
            self::permutations(['A']),
        );
    }

    public function testTwoElements(): void
    {
        self::assertSame(
            [
                ['A', 'B'],
                ['B', 'A'],
            ],
            self::permutations(['A', 'B']),
        );
    }

    public function testThreeElements(): void
    {
        self::assertSame(
            [
                ['A', 'B', 'C'],
                ['B', 'A', 'C'],
                ['C', 'A', 'B'],
                ['A', 'C', 'B'],
                ['B', 'C', 'A'],
                ['C', 'B', 'A'],
            ],
            self::permutations(['A', 'B', 'C']),
        );
    }

    public function testGeneratorAcceptsTraversable(): void
    {
        self::assertSame(
            [
                ['A', 'B'],
                ['B', 'A'],
            ],
            self::permutations(
                new ArrayIterator(['A', 'B']),
            ),
        );
    }

    public function testGeneratedPermutationCountMatchesCalculator(): void
    {
        $generated = iterator_to_array(
            Combinatorics::permutations(range(1, 6)),
            false,
        );

        self::assertCount(
            Combinatorics::permutationsCount(6)->toInt(),
            $generated,
        );
    }

    /**
     * @template TValue
     *
     * @param iterable<TValue> $values Source values.
     *
     * @return list<list<TValue>> All generated permutations.
     */
    private static function permutations(iterable $values): array
    {
        return iterator_to_array(
            Combinatorics::permutations($values),
            false,
        );
    }
}
