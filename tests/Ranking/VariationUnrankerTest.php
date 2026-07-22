<?php

declare(strict_types=1);

namespace Lishack\Combinatorics\Tests\Ranking;

use Brick\Math\BigInteger;
use Lishack\Combinatorics\Combinatorics;
use Lishack\Combinatorics\Ranking\VariationUnranker;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\TestCase;

#[CoversClass(VariationUnranker::class)]
#[CoversMethod(Combinatorics::class, 'variationUnrank')]
final class VariationUnrankerTest extends TestCase
{
    public function testEmptyVariation(): void
    {
        self::assertSame(
            [],
            Combinatorics::variationUnrank(
                universe: ['A', 'B', 'C'],
                rank: 0,
                k: 0,
            ),
        );
    }

    public function testSingleElementVariations(): void
    {
        $universe = ['A', 'B', 'C'];

        self::assertSame(['A'], Combinatorics::variationUnrank($universe, 0, 1));
        self::assertSame(['B'], Combinatorics::variationUnrank($universe, 1, 1));
        self::assertSame(['C'], Combinatorics::variationUnrank($universe, 2, 1));
    }

    public function testTwoElementVariations(): void
    {
        $universe = ['A', 'B', 'C'];

        self::assertSame(['A', 'B'], Combinatorics::variationUnrank($universe, 0, 2));
        self::assertSame(['A', 'C'], Combinatorics::variationUnrank($universe, 1, 2));
        self::assertSame(['B', 'A'], Combinatorics::variationUnrank($universe, 2, 2));
        self::assertSame(['B', 'C'], Combinatorics::variationUnrank($universe, 3, 2));
        self::assertSame(['C', 'A'], Combinatorics::variationUnrank($universe, 4, 2));
        self::assertSame(['C', 'B'], Combinatorics::variationUnrank($universe, 5, 2));
    }

    public function testThreeElementPermutations(): void
    {
        $universe = ['A', 'B', 'C'];

        self::assertSame(['A', 'B', 'C'], Combinatorics::variationUnrank($universe, 0, 3));
        self::assertSame(['A', 'C', 'B'], Combinatorics::variationUnrank($universe, 1, 3));
        self::assertSame(['B', 'A', 'C'], Combinatorics::variationUnrank($universe, 2, 3));
        self::assertSame(['B', 'C', 'A'], Combinatorics::variationUnrank($universe, 3, 3));
        self::assertSame(['C', 'A', 'B'], Combinatorics::variationUnrank($universe, 4, 3));
        self::assertSame(['C', 'B', 'A'], Combinatorics::variationUnrank($universe, 5, 3));
    }

    public function testLargerUniverse(): void
    {
        $universe = ['A', 'B', 'C', 'D'];

        self::assertSame(['A', 'B', 'C'], Combinatorics::variationUnrank($universe, 0, 3));
        self::assertSame(['A', 'B', 'D'], Combinatorics::variationUnrank($universe, 1, 3));
        self::assertSame(['A', 'C', 'B'], Combinatorics::variationUnrank($universe, 2, 3));
        self::assertSame(['D', 'C', 'B'], Combinatorics::variationUnrank($universe, 23, 3));
    }

    public function testRankAndUnrankAreInverse(): void
    {
        $universe = ['A', 'B', 'C', 'D'];

        foreach (Combinatorics::variations($universe, 3) as $variation) {
            $rank = Combinatorics::variationRank(
                universe: $universe,
                variation: $variation,
            );

            self::assertSame(
                $variation,
                Combinatorics::variationUnrank(
                    universe: $universe,
                    rank: $rank,
                    k: 3,
                ),
            );
        }
    }

    public function testUnrankAndRankAreInverse(): void
    {
        $universe = ['A', 'B', 'C', 'D'];
        $count = Combinatorics::variationsCount(4, 3)->toInt();

        for ($rank = 0; $rank < $count; $rank++) {
            $variation = Combinatorics::variationUnrank(
                universe: $universe,
                rank: $rank,
                k: 3,
            );

            self::assertEquals(
                BigInteger::of($rank),
                Combinatorics::variationRank(
                    universe: $universe,
                    variation: $variation,
                ),
            );
        }
    }
}
