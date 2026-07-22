<?php

declare(strict_types=1);

namespace Lishack\Combinatorics\Tests\Ranking;

use Brick\Math\BigInteger;
use Lishack\Combinatorics\Combinatorics;
use Lishack\Combinatorics\Ranking\VariationRanker;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\TestCase;

#[CoversClass(VariationRanker::class)]
#[CoversMethod(Combinatorics::class, 'variationRank')]
final class VariationRankerTest extends TestCase
{
    public function testEmptyVariation(): void
    {
        self::assertEquals(
            BigInteger::zero(),
            Combinatorics::variationRank(
                universe: ['A', 'B', 'C'],
                variation: [],
            ),
        );
    }

    public function testSingleElementVariations(): void
    {
        $universe = ['A', 'B', 'C'];

        self::assertEquals(BigInteger::of(0), Combinatorics::variationRank($universe, ['A']));
        self::assertEquals(BigInteger::of(1), Combinatorics::variationRank($universe, ['B']));
        self::assertEquals(BigInteger::of(2), Combinatorics::variationRank($universe, ['C']));
    }

    public function testTwoElementVariations(): void
    {
        $universe = ['A', 'B', 'C'];

        self::assertEquals(BigInteger::of(0), Combinatorics::variationRank($universe, ['A', 'B']));
        self::assertEquals(BigInteger::of(1), Combinatorics::variationRank($universe, ['A', 'C']));
        self::assertEquals(BigInteger::of(2), Combinatorics::variationRank($universe, ['B', 'A']));
        self::assertEquals(BigInteger::of(3), Combinatorics::variationRank($universe, ['B', 'C']));
        self::assertEquals(BigInteger::of(4), Combinatorics::variationRank($universe, ['C', 'A']));
        self::assertEquals(BigInteger::of(5), Combinatorics::variationRank($universe, ['C', 'B']));
    }

    public function testThreeElementVariations(): void
    {
        $universe = ['A', 'B', 'C'];

        self::assertEquals(BigInteger::of(0), Combinatorics::variationRank($universe, ['A', 'B', 'C']));
        self::assertEquals(BigInteger::of(1), Combinatorics::variationRank($universe, ['A', 'C', 'B']));
        self::assertEquals(BigInteger::of(2), Combinatorics::variationRank($universe, ['B', 'A', 'C']));
        self::assertEquals(BigInteger::of(3), Combinatorics::variationRank($universe, ['B', 'C', 'A']));
        self::assertEquals(BigInteger::of(4), Combinatorics::variationRank($universe, ['C', 'A', 'B']));
        self::assertEquals(BigInteger::of(5), Combinatorics::variationRank($universe, ['C', 'B', 'A']));
    }

    public function testFluffyUniverse(): void
    {
        $universe = ['Fox', 'Wolf', 'Hyena', 'Wild Dog'];

        self::assertEquals(BigInteger::of(0), Combinatorics::variationRank($universe, ['Fox', 'Wolf', 'Hyena']));
        self::assertEquals(BigInteger::of(1), Combinatorics::variationRank($universe, ['Fox', 'Wolf', 'Wild Dog']));
        self::assertEquals(BigInteger::of(2), Combinatorics::variationRank($universe, ['Fox', 'Hyena', 'Wolf']));
        self::assertEquals(BigInteger::of(23), Combinatorics::variationRank($universe, ['Wild Dog', 'Hyena', 'Wolf']));
    }

    public function testObjectsUsingKeySelector(): void
    {
        $universe = [
            (object) ['id' => 10],
            (object) ['id' => 20],
            (object) ['id' => 30],
        ];

        self::assertEquals(
            BigInteger::of(3),
            Combinatorics::variationRank(
                universe: $universe,
                variation: [$universe[1], $universe[2]],
                keySelector: static fn (object $value): int => $value->id,
            ),
        );
    }

    public function testAllRanksAreUnique(): void
    {
        $expected = 0;

        foreach (Combinatorics::variations(['A', 'B', 'C'], 2) as $variation) {
            self::assertEquals(
                BigInteger::of($expected++),
                Combinatorics::variationRank(
                    ['A', 'B', 'C'],
                    $variation,
                ),
            );
        }
    }
}
