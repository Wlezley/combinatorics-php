<?php

declare(strict_types=1);

namespace Lishack\Combinatorics\Tests\Ranking;

use Brick\Math\BigInteger;
use Lishack\Combinatorics\Combinatorics;
use Lishack\Combinatorics\Ranking\PermutationUnranker;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\TestCase;

#[CoversClass(PermutationUnranker::class)]
#[CoversMethod(Combinatorics::class, 'permutationUnrank')]
final class PermutationUnrankerTest extends TestCase
{
    public function testUnrank(): void
    {
        $universe = ['A', 'B', 'C'];

        self::assertSame(
            ['A', 'B', 'C'],
            Combinatorics::permutationUnrank(
                universe: $universe,
                rank: 0,
            ),
        );

        self::assertSame(
            ['A', 'C', 'B'],
            Combinatorics::permutationUnrank(
                universe: $universe,
                rank: 1,
            ),
        );

        self::assertSame(
            ['B', 'C', 'A'],
            Combinatorics::permutationUnrank(
                universe: $universe,
                rank: 3,
            ),
        );

        self::assertSame(
            ['C', 'B', 'A'],
            Combinatorics::permutationUnrank(
                universe: $universe,
                rank: 5,
            ),
        );
    }

    public function testUnrankAndRankAreInverse(): void
    {
        $universe = ['A', 'B', 'C', 'D'];
        $count = Combinatorics::permutationsCount(4)->toInt();

        for ($rank = 0; $rank < $count; $rank++) {
            $permutation = Combinatorics::permutationUnrank(
                universe: $universe,
                rank: $rank,
            );

            self::assertEquals(
                BigInteger::of($rank),
                Combinatorics::permutationRank(
                    universe: $universe,
                    permutation: $permutation,
                ),
            );
        }
    }
}
