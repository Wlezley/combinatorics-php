<?php

declare(strict_types=1);

namespace Lishack\Combinatorics\Tests\Ranking;

use Brick\Math\BigInteger;
use Lishack\Combinatorics\Combinatorics;
use Lishack\Combinatorics\Ranking\PermutationRanker;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\TestCase;

#[CoversClass(PermutationRanker::class)]
#[CoversMethod(Combinatorics::class, 'permutationRank')]
final class PermutationRankerTest extends TestCase
{
    public function testRank(): void
    {
        $universe = ['A', 'B', 'C'];

        self::assertEquals(
            BigInteger::of(0),
            Combinatorics::permutationRank(
                universe: $universe,
                permutation: ['A', 'B', 'C'],
            ),
        );

        self::assertEquals(
            BigInteger::of(1),
            Combinatorics::permutationRank(
                universe: $universe,
                permutation: ['A', 'C', 'B'],
            ),
        );

        self::assertEquals(
            BigInteger::of(3),
            Combinatorics::permutationRank(
                universe: $universe,
                permutation: ['B', 'C', 'A'],
            ),
        );

        self::assertEquals(
            BigInteger::of(5),
            Combinatorics::permutationRank(
                universe: $universe,
                permutation: ['C', 'B', 'A'],
            ),
        );
    }

    public function testRankAndUnrankAreInverse(): void
    {
        $universe = ['A', 'B', 'C', 'D'];

        foreach (Combinatorics::permutations($universe) as $permutation) {
            $rank = Combinatorics::permutationRank(
                universe: $universe,
                permutation: $permutation,
            );

            self::assertSame(
                $permutation,
                Combinatorics::permutationUnrank(
                    universe: $universe,
                    rank: $rank,
                ),
            );
        }
    }
}
